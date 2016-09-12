<?php
ini_set('display_errors','On');
error_reporting(E_ALL);

function get_FolderCountOLD($oid,$parentid)
{
	$out=0;
	
	$qry = "select F.fsid,F.fscid,F.parentid from jest..jestFileStoreCategory as F where F.oid=".(int) $oid." and F.parentid=".(int) $parentid.";";
	$res = mssql_query($qry);
	$out = mssql_num_rows($res);
	
	return $out;
}

function get_FSCountOLD($oid,$fscid)
{
	$out=array('fld'=>0,'fle'=>0);
	$qry = "select F.fsid,F.fscid,F.parentid from jest..jestFileStoreCategory as F where F.oid=".(int) $oid." and F.parentid=".(int) $fscid.";";
	$res = mssql_query($qry);
	$out['fld'] = mssql_num_rows($res);
	$out['fle'] = get_FileCount($oid,$fscid);
	
	if ($out['fld'] > 0)
	{		
		while ($row = mssql_fetch_array($res))
		{
			$out['fld']=$out['fld']+get_FolderCount($oid,$row['fscid']);
			$out['fle']=$out['fle']+get_FileCount($oid,$fscid);
		}
	}
	
	return $out;
}

function arCnt($ar)
{
	$out=0;
	
	if (is_array($ar))
	{
		foreach ($ar as $n=>$v)
		{
			$out++;
			$out=$out+arCnt($v);
		}
	}
	
	return $out;
}

function flattenArray(array $array){
	$ret_array = array();
	foreach(new RecursiveIteratorIterator(new RecursiveArrayIterator($array)) as $value)
	{
	   $ret_array[] = $value;
	}
	return $ret_array;
}

function array_flatten($array) { 
	if (!is_array($array)) { 
		return FALSE; 
	}
	
	$result = array(); 
	foreach ($array as $key => $value) { 
		if (is_array($value)) { 
			$result = array_merge($result, array_flatten($value)); 
		} 
		else { 
			$result[$key] = $value;
		} 
	} 
	return $result;
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

function get_Files($oid,$folders)
{
	$out=array();
	
	if (is_array($folders))
	{
		foreach ($folders as $n => $v)
		{			
			$qry = "select F.docid,F.fscid from jest..jestFileStore as F where F.oid=".(int) $oid." and F.fscid=".(int) $v.";";
			$res = mssql_query($qry);
			while ($row = mssql_fetch_array($res))
			{
				$out[]=$row['docid'];
			}
		}
	}
	
	return $out;
}

function get_FolderInfo($oid,$parentid)
{
	$folders=array();
	$qry = "select F.fsid,F.fscid,F.parentid from jest..jestFileStoreCategory as F where F.oid=".(int) $oid." and F.parentid=".(int) $parentid.";";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);
	
	if ($nrow > 0)
	{		
		while ($row = mssql_fetch_array($res))
		{
			$folders[$row['fscid']][0]=get_FolderInfo($oid,$row['fscid']);
			$folders[$row['fscid']][1]=$row['fscid'];
		}
	}
	
	return $folders;
}

function get_FSInfo($oid,$fscid)
{
	$folders=flattenArray(get_FolderInfo($oid,$fscid));
	$folders[]=$fscid;
	$files	=array();
	
	if (is_array($folders) and arCnt($folders) > 0)
	{
		$files=get_Files($oid,$folders);
	}
	
	$folders = array_diff($folders, array($fscid));	
	return array('folder_cnt'=>count($folders),'file_cnt'=>count($files),'folder_arr'=>$folders,'file_arr'=>$files);
}

function show_FileStoreFileList($oid,$fsaccess,$hidden)
{
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
				echo "					<form action=\"http://jms.bhnmi.com/export/fileout.php\" target=\"_new\" method=\"post\">\n";
				echo "					<input type=\"hidden\" name=\"docid\" value=\"".$vf['docid']."\">\n";
				echo "					<input type=\"hidden\" name=\"storetype\" value=\"file\">\n";
				echo "					<input class=\"transnb\" type=\"image\" src=\"images/download.gif\" alt=\"Download File\">\n";
				echo "					</form>\n";
			}
			else
			{
				echo "<a href=\"http://jms.bhnmi.com/subs/showimage.php?docid=".$vf['docid']."\"><img class=\"JMSimgtooltip\" src=\"export/fileout.php?storetype=file&docid=".$vf['docid']."\" height=\"16px\" width=\"16px\" title=\"".$vf['filename']."\"></a>\n";
			}
		}
		
		echo "				</td>\n";
		echo "			</tr>\n";
	}
	
	
		
	echo "						</table>\n";
}

function get_systemLogIds($t)
{
	$out='';
	
	if (isset($t) && $t != '')
	{
		$qryA = "select docid from jest..jestFileStore where oid=".$oid." and filename='".trim($_FILES['nfuserfile']['name'])."' and active=1;";
		$resA = mssql_query($qryA);
		$nrowA= mssql_num_rows($resA);
		
		if ($nrowA==0)
		{
			$fstore=storefile_FS($_FILES['nfuserfile']['name'],$_FILES['nfuserfile']['tmp_name'],$_FILES['nfuserfile']['size'],$_FILES['nfuserfile']['type'],$cid,$oid,$parentid,$nfuid);

			if (!$fstore)
			{
				$err_cnt++;
				$err_txt=$err_txt."File: ".$_FILES['nfuserfile']['name']."<br>Error: Storage Error<br>Result: No Action Taken<p />";	
			}
		}
		else
		{
			$err_cnt++;
			$err_txt=$err_txt."File: ".$_FILES['nfuserfile']['name']."<br>Error: File Exists in File Cabinet<br>Result: No Action Taken<p />";	
		}
	}
	
	return $out;
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
	elseif ($f == 'application/x-zip-compressed')
	{
		$out = "<img src=\"images/page_white_zip.png\">";
	}
	else
	{
		$out = "<img src=\"images/page_white.png\">";
	}
	
	return $out;
}

function stubfileimages_tree_JSON($f)
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
		$out='images/page_white_camera.png';
	}
	elseif ($f == 'plain/text')
	{
		$out = 'images/page_white_text.png';
	}
	elseif ($f == 'application/pdf')
	{
		$out = 'images/page_white_acrobat.png';
	}
	elseif (
			$f == 'application/vnd.ms-excel' ||
			$f == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
			)
	{
		$out = 'images/page_white_excel.png';
	}
	elseif (
			$f == 'application/vnd.ms-word' ||
			$f == 'application/msword' ||
			$f == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
			)
	{
		$out = 'images/page_white_word.png';
	}
	elseif ($f == 'application/x-zip-compressed')
	{
		$out = 'images/page_white_zip.png';
	}
	else
	{
		$out = 'images/page_white.png';
	}
	
	return $out;
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
	
	$qry0 = "select fsid,fscid,parentid,fscatname,comment from jest..jestFileStoreCategory where parentid=0 and oid=".$oid." and fctype=".$fc." and slevel <= ".$sl." and active=".$act." order by longstorage asc, fscatname asc;";
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

function build_tree_array_JSON($oid,$sl,$fc,$act,$incfiles)
{
	ini_set('display_errors','On');
    error_reporting(E_ALL);
	
	$out	=array();
	//$fc_ar	=array('data'=>'');
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
	
	$qry0 = "select fsid,cast(fscid as varchar) as fscid,parentid,fscatname,comment from jest..jestFileStoreCategory where parentid=0 and oid=".$oid." and fctype=".$fc." and slevel <= ".$sl." and active=".$act." order by longstorage asc, fscatname asc;";
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
				//$fc_ar[]=array('data'=>$row0['fscatname'],'children'=>array('subChild1','subChild2'));
				$subar[]=build_tree_subarray_JSON($row0['fscid'],$oid,$act,$incfiles);
				
				if ($incfiles)
				{
					$qry1 = "select docid,filename,filetype from jest..jestFileStore where oid=".$oid." and fscid=".$row0['fscid']." and active=".$act." order by filename asc;";
					$res1 = mssql_query($qry1);
					$nrow1= mssql_num_rows($res1);
					
					if ($nrow1 > 0)
					{
						while ($row1 = mssql_fetch_array($res1))
						{
							//$subar[]=array('data'=>$row1['filename'],'attr'=>array('id'=>$row1['docid']));
							$subar[]=array(
									   'data'=>array(
													 //'title'=>htmlspecialchars($row1['filename']),
													 'title'=>$row1['filename'],
													 'attr'=>array(
																   'id'=>'filenode_'.$row1['docid'],
																   'href'=>"export/fileout.php?docid=".$row1['docid']."&storetype=file"
																   ),
													 'icon'=>stubfileimages_tree_JSON($row1['filetype'])
													 )
									   );
						}
					}
				}

				if (isset($subar) and count($subar) > 0)
				{
					$fc_ar[]=array(
									'data'=>array(
												 'title'=>$row0['fscatname'],
												 'attr'=>array(
																 'id'=>'foldnode_'.$row0['fscid']
															 ),
												 'icon'=>'folder'
									 ),
									'children'=>$subar
									);
				}
				else
				{
					$fc_ar[]=array(
									'data'=>array(
												'title'=>$row0['fscatname'],
												'attr'=>array(
																'id'=>'foldnode_'.$row0['fscid']
															),
												'icon'=>'folder')
									);
				}
			}
		}
	}
	
	return $out=$fc_ar;
}

function build_tree_subarray_JSON($pid,$oid,$act,$incfiles)
{
	$fc_ar =array();
	
	$qry0 = "select fsid,fscid,parentid,fscatname,comment from jest..jestFileStoreCategory where parentid=".$pid." and active=".$act." order by fscatname asc;";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);
	
	if ($nrow0 > 0)
	{
		while ($row0 = mssql_fetch_array($res0))
		{
			$subar=array();
			$subar[]=build_tree_subarray_JSON($row0['fscid'],$oid,$act,$incfiles);
			
			if ($incfiles)
			{
				$qry1 = "select docid,filename,filetype from jest..jestFileStore where oid=".$oid." and fscid=".$row0['fscid']." and active=".$act." order by filename asc;";
				$res1 = mssql_query($qry1);
				$nrow1= mssql_num_rows($res1);
				
				if ($nrow1 > 0)
				{
					while ($row1 = mssql_fetch_array($res1))
					{
						$subar[]=array(
									   'data'=>array(
													 //'title'=>htmlspecialchars($row1['filename']),
													 'title'=>$row1['filename'],
													 'attr'=>array(
																   'id'=>'filenode_'.$row1['docid'],
																   'href'=>"export/fileout.php?docid=".$row1['docid']."&storetype=file"
																   ),
													 'icon'=>stubfileimages_tree_JSON($row1['filetype'])
													 )
									   );
					}
				}
			}
			
			if (isset($subar) and count($subar) > 0)
			{
				$fc_ar[]=array(
							   'data'=>array(
											'title'=>$row0['fscatname'],
											'attr'=>array(
															'id'=>'foldnode_'.$row0['fscid']
														),
											'icon'=>'folder'
								),
							   'children'=>$subar
							   );
			}
			else
			{
				$fc_ar[]=array(
							   'data'=>array(
											'title'=>$row0['fscatname'],
											'attr'=>array(
															'id'=>'foldnode_'.$row0['fscid']
														),
											'icon'=>'folder')
									);
			}
		}
	}
	
	return $fc_ar;
}

function security_selector($name,$id,$acl,$n,$fscid)
{
	$out='';
	$out=$out."<span class=\"ChangeFolderSecurity\" id=\"".$fscid."\">";
	$out=$out."<select name=\"".$name."\" id=\"".$id."\" title=\"Sets the lowest User Access level that will be allow to View this Folder and its Contents\">\n";
	
	for ($i=$acl;$i >= 0; $i--)
	{
		if ($i==$n)
		{
			$out=$out."<option value=\"".$i."\" SELECTED>".$i."</option>\n";
		}
		else
		{
			$out=$out."<option value=\"".$i."\">".$i."</option>\n";
		}
	}
	
	$out=$out."</select>\n";
	$out=$out."</span>\n";
	
	return $out;
}

function update_Folder_security($oid,$fscid,$nsec)
{
	$qry0 = "update jest..jestFileStoreCategory set slevel=".$nsec." where fscid=".$fscid.";";
	$res0 = mssql_query($qry0);
	//$nrow0= mssql_num_rows($res0);
}

function add_Folder($oid,$parentid,$foldername)
{
	$out=array();
	
	if (isset($foldername) and !empty($foldername))
	{
		$qry0 = "select fsid,slevel,longstorage from jest..jestFileStoreCategory
				where oid=".$oid." and parentid=".$parentid." and fscatname='".trim($foldername)."';";
		$res0 = mssql_query($qry0);
		$row0 = mssql_fetch_array($res0);
		$nrow0= mssql_num_rows($res0);
			
		if ($nrow0==0)
		{			
			$sid=$_SESSION['securityid'];
			$longstorage=(isset($row0['longstorage']) and $row0['longstorage']!=0)? $row0['longstorage']: 0;
			$slevel=(isset($row0['slevel']) and $row0['slevel']!=0)? $row0['slevel']: 0;
			
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
						,longstorage
					) values (
						 @tfscid
						,'".trim($foldername)."'
						,0
						,".$slevel."
						,1
						,".$oid."
						,".$parentid."
						,".$sid."
						,".$longstorage."
					);
					
					commit
					";
			
			$res1 = mssql_query($qry1);
		}
	}
	
	return $out;
}

function get_Permissions($oid,$fscid,$acl)
{
	ini_set('display_errors','On');
    error_reporting(E_ALL);
	$nodetype='folder';
	$dataout='html';
	$odata='';
	$data_ar=array();
	//$acl_ar=array(9,8,7,6,5,4,3,2,1,0);
	
	$acl_ar=array();
	for ($a=$acl;$a >= 0;$a--)
	{
		$acl_ar[]=$a;
	}
	
	if (isset($nodetype) and $nodetype=='folder')
	{
		if ($fscid==0)
		{
			$nrow=1;
			$acv=5;
			$slevel=$acl;
		}
		else
		{
			$qry = "
			select 
				C.fsid,C.fscid,C.slevel,C.active
			from 
				jest..jestFileStoreCategory as C
			where
				C.oid=".$oid."
				and C.fscid=".$fscid."
			";
			$res = mssql_query($qry);
			$row = mssql_fetch_array($res);
			$nrow= mssql_num_rows($res);
			$acv=$row['active'];
			$slevel=$row['slevel'];
		}
		
		if ($nrow > 0)
		{
			if ($acv==0)
			{
				$slevel=9;
			}
			else
			{
				$slevel=$slevel;
			}
			
			$qry1 = "
			select 
				S.securityid,S.officeid,S.fname,S.lname,S.filestoreaccess,(select name from offices where officeid=S.officeid) as oname
			from 
				jest..security as S
			where
				S.officeid=".$oid."
				and S.filestoreaccess <= ".$acl."
				and substring(S.slevel,13,1) >= 1
			order by lname asc
			";
			$res1 = mssql_query($qry1);
			$nrow1= mssql_num_rows($res1);
			
			$qry2 = "
			select 
				S.securityid,S.officeid,S.fname,S.lname,S.filestoreaccess,(select name from offices where officeid=S.officeid) as oname
			from 
				jest..security as S
			where
				S.officeid=89
				and S.filestoreaccess <= ".$acl."
				and substring(S.slevel,13,1) >= 1
			order by lname asc
			";
			$res2 = mssql_query($qry2);
			$nrow2= mssql_num_rows($res2);
			
			if ($nrow1 > 0)
			{
				while($row1 = mssql_fetch_array($res1))
				{
					$data_ar[]=array('sid'=>$row1['securityid'],'acl'=>$row1['filestoreaccess'],'fname'=>$row1['fname'],'lname'=>$row1['lname'],'oname'=>$row1['oname']);
				}
			}
			
			if ($nrow2 > 0 and $oid!=89)
			{
				while($row2 = mssql_fetch_array($res2))
				{
					$data_ar[]=array('sid'=>$row2['securityid'],'acl'=>$row2['filestoreaccess'],'fname'=>$row2['fname'],'lname'=>$row2['lname'],'oname'=>$row2['oname']);
				}
			}
			
			$ccnt=0;
			if (count($data_ar) > 0)
			{
				if ($dataout=='html')
				{
					$folderchain=build_folder_chain($oid,$fscid);
					$odata=$odata."			<table class=\"transnb\">\n";
					$odata=$odata."			<thead>\n";
					$odata=$odata."				<tr>\n";
					$odata=$odata."					<td align=\"left\" colspan=\"3\" class=\"white\">Permissions for ".$folderchain."</td>\n";
					$odata=$odata."					<td align=\"right\" colspan=\"2\" lass=\"white\">".(count($data_ar))." Item(s) Found</td>\n";
					$odata=$odata."				</tr>\n";
					$odata=$odata."					<td align=\"left\" colspan=\"5\" class=\"white\">Folder Security Level: ".security_selector('set_fscid_sec','set_fscid_sec',$acl,$slevel,$fscid)."</td> \n";
					$odata=$odata."				<tr>\n";
					$odata=$odata."					<td align=\"center\" class=\"white\" width=\"20px\"><img src=\"../images/pixel.gif\"></td>\n";
					$odata=$odata."					<td align=\"center\" class=\"white\" width=\"100px\"><b>User Access</b></td>\n";
					$odata=$odata."					<td align=\"left\" class=\"white\" width=\"200px\"><b>Name</b></td>\n";
					$odata=$odata."					<td align=\"right\" class=\"white\" width=\"130px\"><img src=\"../images/pixel.gif\"></td>\n";
					$odata=$odata."					<td align=\"right\" class=\"white\" width=\"200px\"><img src=\"../images/pixel.gif\"></td>\n";
					$odata=$odata."				</tr>\n";
					$odata=$odata."			</thead>\n";
					$odata=$odata."			<tbody>\n";
					
					foreach($data_ar as $n => $v)
					{
						$ccnt++;
						if ($ccnt%2)
						{
							$tbgFL1 = 'even';
						}
						else
						{
							$tbgFL1 = 'odd';
						}
						
						$odata=$odata."				<tr class=\"".$tbgFL1."\">\n";
						$odata=$odata."					<td align=\"center\" width=\"20px\"><img src=\"../images/pixel.gif\"></td>\n";
						$odata=$odata."					<td align=\"center\" width=\"100px\">".$v['acl']."</td>\n";
						$odata=$odata."					<td align=\"left\" width=\"200px\">".$v['lname'].", ".$v['fname']."</td>\n";
						$odata=$odata."					<td align=\"left\" width=\"150px\">".$v['oname']."</td>\n";
						$odata=$odata."					<td align=\"left\" width=\"200px\">".get_Permission_help($v['acl'])."</td>\n";
						$odata=$odata."				</tr>\n";
					}
					
					$odata=$odata."			</tbody>\n";
					$odata=$odata."			</table>\n";
				}
				else
				{
					$odata=json_encode($data_ar);
				}
			}
		}
	}
	
	return $odata;
}

function parse_tree_live2($t_ar,$fsaccess)
{
	echo "<div class=\"FileListDetail\" id=\"0\">";
	echo "HOME";
	echo "</div>\n";
	
	echo "<ul id=\"file_tree2\" class=\"treeview\">\n";

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
			
			echo "<div class=\"FileListDetail\" id=\"".$n."\">";
			echo $v[0][0];
			echo "</div>\n";
			echo "</span>\n";			
			echo "<ul>\n";
			
			if (is_array($v[1]))
			{
				parse_tree_node($v[1],$fsaccess);
			}
			
			echo "</ul>\n";
			echo "</li>\n";
		}
	}
	
	echo "</ul>\n";
}

function parse_tree_node2($t_ar,$fsaccess)
{	
	foreach ($t_ar as $n => $v)
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
			
			echo "<div class=\"FileListDetail\" id=\"".$n."\">";
			echo $v[0][0];
			echo "</div>\n";
			echo "</span>\n";			
			echo "<ul>\n";
			
			if (is_array($v[1]))
			{
				parse_tree_node($v[1],$fsaccess);
			}
			
			echo "</ul>\n";
			echo "</li>\n";
		}
	}
}

function parse_tree_live($t_ar,$fsaccess,$addfldr)
{
	ini_set('display_errors','On');
    error_reporting(E_ALL);

	/*
	echo "<div id=\"treecontrol\">\n";
	echo "<a href=\"?#\"><img src=\"images/folder-closed.gif\" title=\"Collapse All\"></a>";
	echo " | <a href=\"?#\"><img src=\"images/folder.gif\" title=\"Expand All\"></a>";
	
	if ($addfldr and $fsaccess >= 5)
	{
		echo " | <img class=\"ShowFileEditControl\" src=\"images/folder_edit.png\" height=\"14px\" width=\"14px\" title=\"Toggle Folder & File Edit On/Off\">";
		echo "<div id=\"EditOn\">Editting On</div>\n";
	}
	
	echo "</div>\n";
	*/
	echo "HOME";
	
	if ($addfldr and $fsaccess >= 99)
	{
		echo "<div class=\"FileEditControl\">\n";
		echo "	<img class=\"FolderAdd\" src=\"images/folder_add.png\" id=\"0\" height=\"14px\" width=\"14px\" title=\"Click to add a Folder\">\n";
		echo "</div>\n";
	}
	
	echo "<ul id=\"file_tree2\" class=\"filetree\">\n";

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
			
			if ($addfldr and $fsaccess >= 99)
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
					echo "		<a class=\"JMStooltip\" href=\"http://jms.bhnmi.com/export/fileout.php?docid=".$fn."&storetype=file\" target=\"_new\" title=\"Click to Download\">".stubfileimages_tree($fv[1])."</a>\n";
					
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

function parse_tree_live_HTML($t_ar,$fsaccess,$addfldr)
{
	$out='';
	ini_set('display_errors','On');
    error_reporting(E_ALL);
	$out=$out."HOME";
	if ($addfldr and $fsaccess >= 5)
	{
		$out=$out."<div class=\"FileEditControl\">\n";
		$out=$out."	<a href=\"#\"><img class=\"FolderAdd\" src=\"images/folder_add.png\" id=\"0\" height=\"14px\" width=\"14px\" title=\"Click to add a Folder\"></a>\n";
		$out=$out."</div>\n";
	}
	
	$out=$out."<ul id=\"file_treeHTML\" class=\"filetree\">\n";

	foreach ($t_ar as $n => $v) // 1st Tier Kick-off
	{
		if (is_array($v))
		{
			$out=$out."<li>";
			
			if (isset($v[0][1]) and strlen($v[0][1]) > 3)
			{
				$out=$out."<span class=\"folder JMStooltip\" title=\"".$v[0][1]."\">";
			}
			else
			{
				$out=$out."<span class=\"folder\">";
			}
			
			$out=$out.$v[0][0];
			$out=$out."</span>\n";
			
			if ($addfldr and $fsaccess >= 5)
			{
				$out=$out."<div class=\"FileEditControl\">\n";
				$out=$out."	<img class=\"FolderAdd\" src=\"images/folder_add.png\" id=\"".$n."\" height=\"14px\" width=\"14px\" title=\"Click to add a Folder\">\n";
				
				if ($fsaccess >= 6)
				{
					$out=$out."<img class=\"FolderDelete\" src=\"images/bin.png\" id=\"".$n."\" height=\"14px\" width=\"14px\" title=\"Click to Delete this Folder\">\n";
				}
				
				$out=$out."	<img class=\"FileAdd\" src=\"images/folder_go.png\" id=\"".$n."\" height=\"14px\" width=\"14px\" title=\"Click to add a File to this Folder\">\n";
				$out=$out."</div>\n";
			}
			
			$out=$out."<ul>\n";
			
			if (is_array($v[1]))
			{
				$out=$out.parse_tree_node_HTML($v[1],$fsaccess,$addfldr);
			}
			
			if (is_array($v[2]) and count($v[2]) > 0)
			{
				foreach ($v[2] as $fn => $fv)
				{
					$out=$out."<li>\n";
					$out=$out."	<span class=\"file\">".$fv[0]." ";
					$out=$out."		<a class=\"JMStooltip\" href=\"http://jms.bhnmi.com/export/fileout.php?docid=".$fn."&storetype=file\" target=\"_new\" title=\"Click to Download\">".stubfileimages_tree($fv[1])."</a>\n";
					
					if ($addfldr and $fsaccess >= 6)
					{
						$out=$out."<div class=\"FileEditControl\">\n";
						$out=$out."	<img class=\"FileDelete\" src=\"images/bin.png\" id=\"".$fn."\" height=\"14px\" width=\"14px\" title=\"Click to Delete this File\">\n";
						$out=$out."</div>\n";
					}
					
					$out=$out."	</span>\n";
					$out=$out."</li>\n";
				}
			}
			
			$out=$out."</ul>\n";
			$out=$out."</li>\n";
		}
	}
	
	$out=$out."</ul>\n";
	
	return $out;
}

function parse_tree_node_HTML($t_ar,$fsaccess,$addfldr)
{
	$out='';
	foreach ($t_ar as $n => $v)
	{
		if (is_array($v))
		{
			$out=$out."<li>";
			
			if (isset($v[0][1]) and strlen($v[0][1]) > 3)
			{
				$out=$out."<span class=\"folder JMStooltip\" title=\"".$v[0][1]."\">";
			}
			else
			{
				$out=$out."<span class=\"folder\">";
			}
			
			$out=$out. $v[0][0];
			$out=$out."</span>\n";
			
			if ($addfldr and $fsaccess >= 5)
			{
				$out=$out."<div class=\"FileEditControl\">\n";
				$out=$out."	<img class=\"FolderAdd\" src=\"images/folder_add.png\" id=\"".$n."\" height=\"14px\" width=\"14px\" title=\"Click to add a Folder\">\n";
				
				if ($fsaccess >= 6)
				{
					$out=$out."	<img class=\"FolderDelete\" src=\"images/bin.png\" id=\"".$n."\" height=\"14px\" width=\"14px\" title=\"Click to Delete this Folder\">\n";
				}
				
				$out=$out."	<img class=\"FileAdd\" src=\"images/folder_go.png\" id=\"".$n."\" height=\"14px\" width=\"14px\" title=\"Click to add a File to this Folder\">\n";
				$out=$out."</div>\n";
			}
			
			$out=$out."<ul>\n";
			
			if (is_array($v[1]))
			{
				$out=$out.parse_tree_node_HTML($v[1],$fsaccess,$addfldr);
			}
			
			if (is_array($v[2]) and count($v[2]) > 0)
			{
				foreach ($v[2] as $fn => $fv)
				{
					$out=$out."<li>\n";
					$out=$out."	<span class=\"file\"> ".$fv[0]." ";
					$out=$out."		<a class=\"JMStooltip\" href=\"http://jms.bhnmi.com/export/fileout.php?docid=".$fn."&storetype=file\" target=\"_new\" title=\"Click to Download\">".stubfileimages_tree($fv[1])."</a>\n";
					
					if ($fsaccess >= 6)
					{
						$out=$out."<div class=\"FileEditControl\">\n";
						$out=$out."	<img class=\"FileDelete\" src=\"images/bin.png\" id=\"".$fn."\" height=\"14px\" width=\"14px\" title=\"Click to Delete this File\">\n";
						$out=$out."</div>\n";
					}
					
					$out=$out."	</span>\n";
					$out=$out."</li>\n";
				}
			}
			
			$out=$out."</ul>\n";
			$out=$out."</li>\n";
		}
	}
	
	return $out;
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
			
			if ($addfldr and $fsaccess >= 99)
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
					echo "		<a class=\"JMStooltip\" href=\"http://jms.bhnmi.com/export/fileout.php?docid=".$fn."&storetype=file\" target=\"_new\" title=\"Click to Download\">".stubfileimages_tree($fv[1])."</a>\n";
					
					if ($fsaccess >= 99)
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

function build_folder_chain($oid,$fscid)
{
	$rootp='';
	$out='';
	
	$qry0 = "select fscid,parentid,fscatname from jest..jestFileStoreCategory where oid=".$oid." and fscid=".$fscid.";";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);
	
	if ((isset($_REQUEST['subq']) and $_REQUEST['subq']!='get_Permissions') and $fscid==0)
	{
		$rootp=$rootp." <span class=\"ShowPermissions\" id=\"0\"><img src=\"../images/folder_key.png\" title=\"Folder Permissions\"></span>";
	}

	if ($nrow0 > 0)
	{
		$row0 = mssql_fetch_array($res0);
		//$out[]=$row0['fscatname'];
		$out=" > <a href=\"#\" class=\"FileListDetail JMStooltip\" id=\"".$row0['fscid']."\">".$row0['fscatname'].'</a>'.$out;
		
		if ($row0['parentid']!=0)
		{
			$qry1 = "select fscid,parentid,fscatname from jest..jestFileStoreCategory where oid=".$oid." and fscid=".$row0['parentid'].";";
			$res1 = mssql_query($qry1);
			$nrow1= mssql_num_rows($res1);
			
			if ($nrow1 > 0)
			{
				$row1 = mssql_fetch_array($res1);
				//$out[]=$row1['fscatname'];
				$out="  <a href=\"#\" class=\"FileListDetail JMStooltip\" id=\"".$row1['fscid']."\">".$row1['fscatname'].'</a>'.$out;
				
				if ($row1['parentid']!=0)
				{
					$qry2 = "select fscid,parentid,fscatname from jest..jestFileStoreCategory where oid=".$oid." and fscid=".$row1['parentid'].";";
					$res2 = mssql_query($qry2);
					$nrow2= mssql_num_rows($res2);
					
					if ($nrow2 > 0)
					{
						$row2 = mssql_fetch_array($res2);
						//$out[]=$row2['fscatname'];
						$out=" > <a href=\"#\" class=\"FileListDetail JMStooltip\" id=\"".$row2['fscid']."\">".$row2['fscatname'].'</a>'.$out;
						
						if ($row2['parentid']!=0)
						{
							$qry3 = "select fscid,parentid,fscatname from jest..jestFileStoreCategory where oid=".$oid." and fscid=".$row2['parentid'].";";
							$res3 = mssql_query($qry3);
							$nrow3= mssql_num_rows($res3);
							
							if ($nrow3 > 0)
							{
								$row3 = mssql_fetch_array($res3);
								$out=" > <a href=\"#\" class=\"FileListDetail JMStooltip\" id=\"".$row3['fscid']."\">".$row3['fscatname'].'</a>'.$out;
								
								if ($row3['parentid']!=0)
								{
									$qry4 = "select fscid,parentid,fscatname from jest..jestFileStoreCategory where oid=".$oid." and fscid=".$row3['parentid'].";";
									$res4 = mssql_query($qry4);
									$nrow4= mssql_num_rows($res4);
									
									if ($nrow4 > 0)
									{
										$row4 = mssql_fetch_array($res4);
										$out=" > <a href=\"#\" class=\"FileListDetail JMStooltip\" id=\"".$row4['fscid']."\">".$row4['fscatname'].'</a>'.$out;
										
										if ($row4['parentid']!=0)
										{
											$qry5 = "select fscid,parentid,fscatname from jest..jestFileStoreCategory where oid=".$oid." and fscid=".$row4['parentid'].";";
											$res5 = mssql_query($qry5);
											$nrow5= mssql_num_rows($res5);
											
											if ($nrow5 > 0)
											{
												$row5 = mssql_fetch_array($res5);
												//$out[]=$row5['fscatname'];
												$out=" > <a href=\"#\" class=\"FileListDetail JMStooltip\" id=\"".$row5['fscid']."\">".$row5['fscatname'].'</a>'.$out;
												
												if ($row5['parentid']!=0)
												{
													$qry6 = "select fscid,parentid,fscatname from jest..jestFileStoreCategory where oid=".$oid." and fscid=".$row5['parentid'].";";
													$res6 = mssql_query($qry6);
													$nrow6= mssql_num_rows($res6);
													
													if ($nrow6 > 0)
													{
														$row6 = mssql_fetch_array($res6);
														//$out[]=$row6['fscatname'];
														$out=" > <a href=\"#\" class=\"FileListDetail JMStooltip\" id=\"".$row6['fscid']."\">".$row6['fscatname'].'</a>'.$out;
														
														if ($row6['parentid']!=0)
														{
															$qry7 = "select fscid,parentid,fscatname from jest..jestFileStoreCategory where oid=".$oid." and fscid=".$row6['parentid'].";";
															$res7 = mssql_query($qry7);
															$nrow7= mssql_num_rows($res7);
															
															if ($nrow7 > 0)
															{
																$row7 = mssql_fetch_array($res7);
																//$out[]=$row7['fscatname'];
																$out=" > <a href=\"#\" class=\"FileListDetail JMStooltip\" id=\"".$row7['fscid']."\">".$row7['fscatname'].'</a>'.$out;
																
																if ($row7['parentid']!=0)
																{
																	$qry8 = "select fscid,parentid,fscatname from jest..jestFileStoreCategory where oid=".$oid." and fscid=".$row7['parentid'].";";
																	$res8 = mssql_query($qry8);
																	$nrow8= mssql_num_rows($res8);
																	
																	if ($nrow8 > 0)
																	{
																		$row8 = mssql_fetch_array($res8);
																		//$out[]=$row8['fscatname'];
																		$out=" > <a href=\"#\" class=\"FileListDetail JMStooltip\" id=\"".$row8['fscid']."\">".$row8['fscatname'].'</a>'.$out;
																	}
																}
															}
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
	
	//echo print_r($out);
	//$out="<a class=\"FileListDetail JMStooltip\" id=\"0\" href=\"#\">HOME</a>".$rootp.$out;
	
	return $out;
}

function show_FileStoreListHTML($oid,$fsaccess)
{
	$out='';
	
	$qry0 = "select S.securityid,S.officeid,S.filestoreaccess from security as S where S.securityid=".$_SESSION['securityid'].";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	$nrow0= mssql_num_rows($res0);
	
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
	
	$pfile_ar=array();
	while ($row = mssql_fetch_array($res))
	{
		$pfile_ar[$row['docid']]=$row;
	}
	
	$out=$out."					<table width=\"900px\">\n";
	$out=$out."						<tr>\n";
	$out=$out."							<td colspan=\"2\" align=\"left\"><b>Files & Folders</b></td>\n";
	$out=$out."							<td colspan=\"7\" align=\"right\">\n";
	$out=$out."								<form method=\"post\">\n";
	$out=$out."								<input type=\"hidden\" name=\"action\" value=\"file\">\n";
	$out=$out."								<input type=\"hidden\" name=\"call\" value=\"list_file_OFF\">\n";
	$out=$out."								<table width=\"100%\">\n";
	$out=$out."									<tr>\n";
	$out=$out."										<td align=\"right\" valign=\"bottom\">\n";
	
	if ($row0['filestoreaccess'] == 9)
	{
		$out=$out."											<select name=\"factive\" id=\"factive\" onChange=\"this.form.submit();\">\n";

		if (isset($_REQUEST['factive']) && $_REQUEST['factive']==0)
		{
			$out=$out."											<option value=\"1\">Active</option>\n";
			
			if ($row0['filestoreaccess'] >= 6)
			{
				$out=$out."											<option value=\"0\" SELECTED>Purge List</option>\n";
			}
		}
		else
		{
			$out=$out."											<option value=\"1\" SELECTED>Active</option>\n";
			
			if ($row0['filestoreaccess'] >= 6)
			{
				$out=$out."											<option value=\"0\">Purge List</option>\n";
			}
		}
		
		$out=$out."												</select>\n";
	}
	else
	{
		$out=$out."								<input type=\"hidden\" name=\"factive\" value=\"1\">\n";	
	}

	$out=$out."											</td>\n";
	$out=$out."											<td width=\"20px\"><input id=\"frefresh\" class=\"transnb\" type=\"image\" src=\"images/arrow_refresh_small.png\" title=\"Refresh\"></td>\n";
	$out=$out."										</tr>\n";
	$out=$out."									</table>\n";
	$out=$out."								</form>\n";
	
	//display_array($dfscat_ar);
	
	$out=$out."							</td>\n";
	$out=$out."						</tr>\n";
	$out=$out."						<tr class=\"tblhd\">\n";
	$out=$out."							<td><img src=\"images/pixel.gif\"></td>\n";
	$out=$out."							<td align=\"left\" width=\"175px\">Name</td>\n";
	$out=$out."							<td align=\"left\">Loc</td>\n";
	$out=$out."							<td align=\"center\">Size</td>\n";
	$out=$out."							<td align=\"center\">Uploaded</td>\n";
	$out=$out."							<td align=\"center\">Updated</td>\n";
	$out=$out."							<td><img src=\"images/pixel.gif\"></td>\n";
	$out=$out."							<td><img src=\"images/pixel.gif\"></td>\n";
	$out=$out."							<td><img src=\"images/pixel.gif\"></td>\n";
	$out=$out."						</tr>\n";
	
	$lcnt=0;
	
	if ((isset($_REQUEST['factive']) and $_REQUEST['factive']==0) and $row0['filestoreaccess'] >= 6)
	{
		foreach ($dfscat_ar as $nfl => $vfl)
		{
			$lcnt++;
		
			if ($lcnt%2)
			{
				$tb1='odd';
			}
			else
			{
				$tb1='even';
			}
			
			$out=$out."						<tr class=\"".$tb1."\">\n";
			$out=$out."							<td><img src=\"images/pixel.gif\"></td>\n";
			$out=$out."							<td align=\"left\" width=\"175px\">".$vfl."</td>\n";
			$out=$out."							<td align=\"left\">Folder</td>\n";
			$out=$out."							<td align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
			$out=$out."							<td align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
			$out=$out."							<td align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
			$out=$out."							<td><img src=\"images/pixel.gif\"></td>\n";
			$out=$out."							<td><img src=\"images/pixel.gif\"></td>\n";
			$out=$out."							<td align=\"center\" width=\"20px\">\n";
			$out=$out."								<form method=\"post\">\n";
			$out=$out."								<input type=\"hidden\" name=\"action\" value=\"file\">\n";
			$out=$out."								<input type=\"hidden\" name=\"call\" value=\"delete_folder_OFF\">\n";
			$out=$out."								<input type=\"hidden\" name=\"fscid\" value=\"".$nfl."\">\n";
			$out=$out."								<input type=\"hidden\" name=\"factive\" value=\"0\">\n";	
			$out=$out."								<input class=\"transnb\" type=\"image\" src=\"images/cross.png\" title=\"Delete Folder\">\n";
			$out=$out."								</form>\n";
			$out=$out."							</td>\n";
			$out=$out."						</tr>\n";
		}
	}

	foreach ($pfile_ar as $nf => $vf)
	{
		$lcnt++;
		
		if ($lcnt%2)
		{
			$tb1='odd';
		}
		else
		{
			$tb1='even';
		}
		
		$out=$out."			<tr class=\"".$tb1."\">\n";
		$out=$out."				<td align=\"center\">\n";
		
		$out=$out.stubfileimages(trim($vf['filetype']));
		
		$out=$out."				</td>\n";
		$out=$out."				<td align=\"left\">\n";
		
		if (strlen($vf['filename']) > 25)
		{
			$out=$out."<div title=\"".$vf['filename']."\">".substr($vf['filename'],0,25)."...</div>\n";
		}
		else
		{
			$out=$out."<div title=\"".$vf['filename']."\">".$vf['filename']."</div>\n";
		}
		
		$out=$out. 				"</td>\n";
		$out=$out."				<td>\n";
		
		if ($row0['filestoreaccess'] >= 6)
		{
			$out=$out."					<form method=\"post\">\n";
			$out=$out."					<input type=\"hidden\" name=\"action\" value=\"file\">\n";
			$out=$out."					<input type=\"hidden\" name=\"call\" value=\"change_folder_OFF\">\n";
			$out=$out."					<input type=\"hidden\" name=\"docid\" value=\"".$nf."\">\n";
			
			if (isset($_REQUEST['factive']))
			{
				$out=$out."					<input type=\"hidden\" name=\"factive\" value=\"".$_REQUEST['factive']."\">\n";
			}
			
			$out=$out."					<select name=\"fscid\" id=\"fscid\" onChange=\"this.form.submit();\">\n";
			
			foreach ($fscat_ar as $fn => $fv)
			{
				if ($vf['fscid']==$fn)
				{
					$out=$out."								<option value=\"".$fn."\" SELECTED>".$fv."</option>\n";
				}
				else
				{
					$out=$out."								<option value=\"".$fn."\">".$fv."</option>\n";
				}
			}
			
			$out=$out."					</select>\n";
			$out=$out."					</form>\n";
		}
		else
		{
			echo $row['fscat'];
		}
		
		$out=$out."				</td>\n";
		$out=$out."				<td align=\"right\">".number_format($vf['filesize'])." kb</td>\n";
		$out=$out."				<td align=\"center\">".date('m/d/Y G:i A',strtotime($vf['adate']))."</td>\n";
		$out=$out."				<td align=\"center\">".date('m/d/Y G:i A',strtotime($vf['udate']))."</td>\n";
		$out=$out."				<td align=\"center\" width=\"20px\">\n";
	
		if ($vf['active']==0 && $row0['filestoreaccess'] >= 9)
		{
			$out=$out."					<form method=\"post\">\n";
			$out=$out."					<input type=\"hidden\" name=\"action\" value=\"file\">\n";
			$out=$out."					<input type=\"hidden\" name=\"docid\" value=\"".$vf['docid']."\">\n";
			$out=$out."					<input type=\"hidden\" name=\"call\" value=\"delete_file_OFF\">\n";
			
			if (isset($_REQUEST['factive']))
			{
				$out=$out."					<input type=\"hidden\" name=\"factive\" value=\"".$_REQUEST['factive']."\">\n";
			}
			
			$out=$out."					<input class=\"transnb\" type=\"image\" src=\"images/delete.png\" onClick=\"return ConfirmDeleteFile();\" title=\"Purge File\">\n";
			$out=$out."					</form>\n";
		}
		
		$out=$out."				</td>\n";
		$out=$out."				<td align=\"center\" width=\"20px\">\n";
		$out=$out."					<form method=\"post\">\n";
		$out=$out."					<input type=\"hidden\" name=\"action\" value=\"file\">\n";
		$out=$out."					<input type=\"hidden\" name=\"docid\" value=\"".$vf['docid']."\">\n";
		
		if (isset($_REQUEST['subq']) && !empty($_REQUEST['subq']))
		{
			$out=$out."								<input type=\"hidden\" name=\"subq\" value=\"".$_REQUEST['subq']."\">\n";
		}
		
		if (isset($_REQUEST['factive']))
		{
			$out=$out."					<input type=\"hidden\" name=\"factive\" value=\"".$_REQUEST['factive']."\">\n";
		}
		
		if ($row0['filestoreaccess'] >= 6)
		{
			if ($vf['active']==1)
			{
				$out=$out."					<input type=\"hidden\" name=\"call\" value=\"deactivate_file_OFF\">\n";
				$out=$out."					<input class=\"transnb\" type=\"image\" src=\"images/bin.png\" onClick=\"return ConfirmDeactivateFile();\" alt=\"Deactivate File\">\n";
			}
			else
			{
				$out=$out."					<input type=\"hidden\" name=\"call\" value=\"undelete_file_OFF\">\n";
				$out=$out."					<input class=\"transnb\" type=\"image\" src=\"images/accept.png\" onClick=\"return ConfirmRestoreFile();\" alt=\"Restore File\">\n";
			}
		}
		
		$out=$out."					</form>\n";
		$out=$out."				</td>\n";
		$out=$out."				<td align=\"center\" width=\"20px\">\n";
		
		if ($vf['active']==1)
		{
			if (substr(trim($vf['filetype']),0,5) != 'image')
			{
				$out=$out."					<form action=\"http://jms.bhnmi.com/export/fileout.php\" target=\"_new\" method=\"post\">\n";
				$out=$out."					<input type=\"hidden\" name=\"docid\" value=\"".$vf['docid']."\">\n";
				$out=$out."					<input type=\"hidden\" name=\"storetype\" value=\"file\">\n";
				$out=$out."					<input class=\"transnb\" type=\"image\" src=\"images/download.gif\" alt=\"Download File\">\n";
				$out=$out."					</form>\n";
			}
			else
			{
				$out=$out."<a href=\"http://jms.bhnmi.com/subs/showimage.php?docid=".$vf['docid']."\"><img class=\"JMSimgtooltip\" src=\"export/fileout.php?storetype=file&docid=".$vf['docid']."\" height=\"16px\" width=\"16px\" title=\"".$vf['filename']."\"></a>\n";
			}
		}
		
		$out=$out."				</td>\n";
		$out=$out."			</tr>\n";
	}
	
	
		
	$out=$out."						</table>\n";
	
	return $out;
}

function upload_file_OFF()
{
	ini_set('display_errors','On');
    error_reporting(E_ALL);
	
	$out=array();
	
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
			$uid=md5(session_id().'.'.time().'.'.$n.'.'.$_SESSION['securityid']);
			
			if ($v==0)
			{
				if (!in_array($_FILES['userfile']['type'][$n],$inv_type_ar))
				{
					$qryA = "select docid from jest..jestFileStore where oid=".$oid." and fscid=".$_REQUEST['fscid']." and filename='".trim($_FILES['userfile']['name'][$n])."' and active=1;";
					$resA = mssql_query($qryA);
					$nrowA= mssql_num_rows($resA);
					
					if ($nrowA==0)
					{
						$fstore=storefile_FS($_FILES['userfile']['name'][$n],$_FILES['userfile']['tmp_name'][$n],$_FILES['userfile']['size'][$n],$_FILES['userfile']['type'][$n],$cid,$oid,$_REQUEST['fscid'],$uid);

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
	
	return $out;
}

function add_folder_OFF()
{
	$out='';
	if (isset($_REQUEST['foldername']) and !empty($_REQUEST['foldername']))
	{
		$qryA = "select officeid,securityid,filestoreaccess from security where securityid=".$_SESSION['securityid'].";";
		$resA = mssql_query($qryA);
		$rowA = mssql_fetch_array($resA);
		
		if (isset($rowA['filestoreaccess']) and $rowA['filestoreaccess'] >= 5)
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
				
				$res1 = mssql_query($qry1);
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
	
	return $out;
}

function delete_file_fs($oid,$docid)
{
	$qry = "select * from jest..jestFileStore where oid=".$oid." and docid=".$docid.";";
	$res = mssql_query($qry);
	$nrow = mssql_num_rows($res);
	
	if ($nrow > 0)
	{
		$row=mssql_fetch_array($res);
		
		$filename=addslashes(FILESTORE.$row['filestore'].$row['fsfilename']);
		if (file_exists($filename))
		{
			if (unlink($filename))
			{
				$qry = "delete from jest..jestFileStore where oid=".$oid." and docid=".$docid.";";
				$res = mssql_query($qry);
			}
		}
	}
}

function file_maintenance()
{
	$out=array();
	if (isset($_REQUEST['docid']) and $_REQUEST['docid']!=0)
	{
		if (isset($_REQUEST['ConfirmFSAction']) && $_REQUEST['ConfirmFSAction']=='Delete')
		{
			delete_file_fs((int) $_SESSION['officeid'],(int) $_REQUEST['docid']);
		}
		else
		{
			$qry1 = "update jest..jestFileStore set active=0 where oid=".(int) $_SESSION['officeid']." and docid=".(int) $_REQUEST['docid'].";";
			$res1 = mssql_query($qry1);
		}
	}
	
	return $out;
}

function folder_maintenance()
{
	$out=array();
	
	if (isset($_REQUEST['ndocid']))
	{
		while (list($key1, $docid) = each($_REQUEST['ndocid']))
		{
			if (isset($_REQUEST['ConfirmFSAction']) && $_REQUEST['ConfirmFSAction']=='Delete')
			{
				delete_file_fs((int) $_SESSION['officeid'],(int) $docid);
			}
			else
			{
				$qry1 = "update jest..jestFileStore set active=0 where oid=".(int) $_SESSION['officeid']." and docid=".(int) $docid.";";
				$res1 = mssql_query($qry1);
			}
		}
	}
	
	if (isset($_REQUEST['nfscid']))
	{
		while (list($key2, $fscid) = each($_REQUEST['nfscid']))
		{
			if (isset($_REQUEST['ConfirmFSAction']) && $_REQUEST['ConfirmFSAction']=='Delete')
			{
				$qry2 = "delete from jest..jestFileStoreCategory where oid=".(int) $_SESSION['officeid']." and fscid=".(int) $fscid.";";
				$res2 = mssql_query($qry2);
			}
			else
			{
				$qry2 = "update jest..jestFileStoreCategory set active=0 where oid=".(int) $_SESSION['officeid']." and fscid=".(int) $fscid.";";
				$res2 = mssql_query($qry2);
			}
		}
	}
	
	return $out;
	//print_r($_REQUEST['nfscid']);
}

function delete_folder_OFF()
{
	$out='';
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
	
	return $out;
}

function show_FileStoreTree($oid,$fsaccess)
{
	$tree_ar=build_tree_array($oid,$fsaccess,0,1,true);
	
	if (isset($tree_ar) and is_array($tree_ar) and count($tree_ar) > 0)
	{
		parse_tree_live($tree_ar,$fsaccess,true);
	}
	else
	{
		if ($fsaccess >= 6)
		{
			echo "	<img class=\"FolderAdd\" src=\"images/folder_add.png\" id=\"0\" height=\"14px\" width=\"14px\" title=\"Click to add a Folder\">\n";
		}
	}
	
}

function show_FileStoreTreeHTML($oid,$fsaccess)
{
	$out='';
	
	$tree_ar=build_tree_array($oid,$fsaccess,0,1,true);
	
	if (isset($tree_ar) and is_array($tree_ar) and count($tree_ar) > 0)
	{
		$out.=parse_tree_live_HTML($tree_ar,$fsaccess,true);
	}
	else
	{
		if ($fsaccess >= 6)
		{
			$out.="	<img class=\"FolderAdd\" src=\"images/folder_add.png\" id=\"0\" height=\"14px\" width=\"14px\" title=\"Click to add a Folder\">\n";
		}
	}
	
	return $out;
}

function show_FileStoreTreeJSON($oid,$fsaccess)
{
	$tree_ar=build_tree_array_JSON($oid,$fsaccess,0,1,true);
	//$tree_ar=build_tree_array_JSON($oid,$fsaccess,0,1,false);
	$out='';	
	
	if (isset($tree_ar) and is_array($tree_ar) and count($tree_ar) > 0)
	{
		//parse_tree_JSON($tree_ar,$fsaccess,true);
		
		if ($_SESSION['securityid']==26999999999999999999999)
		{
			$out='<pre>'.print_r($tree_ar).'</pre>';
		}
		else
		{
			$out=$out.json_encode($tree_ar);
		}
	}
	
	return $out;
}

function show_FileStoreCapacity($oid,$fsaccess)
{
	$qry1 = "select officeid,name,fslimit from offices where officeid = ".$oid.";";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	$nrow1= mssql_num_rows($res1);
	
	$qry2 = "select isnull(sum(filesize),0) as cfilesize from jest..jestFileStore as F1 inner join jest..jestFileStoreCategory as F2 on F1.fscid=F2.fscid where F1.oid = ".$oid." and F2.fctype = 0;";
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
	
	$num_files	= 1;
	$max_file_size=10000000;
	
	?>

	<SCRIPT type=text/javascript>
	$(function() {
		
		$("#fsprogressbar").progressbar({
			value: <?php echo $fsperc; ?>
		});

	});
	</SCRIPT>

	<?php
	
	echo "							<table width=\"450px\">\n";
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
}

function show_TrashBin_Icon_OLD($oid)
{
	$odata='';
	
	$qry = "
		select 
			fscid
		from 
			jest..jestFileStoreCategory
		where
			oid=".$_REQUEST['oid']."
			and active=0
	";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);
	
	$qry1 = "
		select 
			docid
		from 
			jest..jestFileStore
		where
			oid=".$_REQUEST['oid']."
			and active=0
	";
	$res1 = mssql_query($qry1);
	$nrow1= mssql_num_rows($res1);
	
	if ($nrow > 0 or $nrow1 > 0)
	{
		$out=$out."<img class=\"JMStooltip\" id=\"list_trash_bin\" src=\"../images/bin.png\" title=\"There are ".($nrow + $nrow1)." item(s) in the Trash Bin\">";
	}
	
	return $out;
}

function get_FolderTree_list($oid,$acclev,$act)
{
	//$tree_ar=build_tree_array($oid,$acclev,0,1,true);
	
	$tree_ar=build_tree_array($oid,$acclev,0,$act,true);
	parse_tree_live($tree_ar,$acclev);
}

function get_FileSearch_list($oid,$acclev)
{
	$dev_ar= array(2699999999999999999999999);
	$odata='';
	
	$qry = "
		select 
			F.docid,F.fscid,F.filename,F.filetype,F.adate,F.oid,(select lname from jest..security where securityid=sid) as owner,
			(select fscatname from jest..jestFileStoreCategory where fscid=F.fscid) as fcname
		from 
			jest..jestFileStore as F
		where
			F.oid=".$_REQUEST['oid']."
			and F.filename like '".$_REQUEST['ffname']."%'
			and F.cid=0
			and F.active=1
		order by
			F.filename asc
	";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);

	$odata=$odata."			<table class=\"transnb\" width=\"100%\">\n";
	$odata=$odata."			<thead>\n";
	$odata=$odata."				<tr>\n";
	$odata=$odata."					<td align=\"center\" colspan=\"2\" class=\"white\"><img src=\"../images/pixel.gif\"></td>\n";
	$odata=$odata."					<td align=\"right\" colspan=\"3\" class=\"white\">".$nrow." File(s) Found</td>\n";
	$odata=$odata."				</tr>\n";
	$odata=$odata."				<tr>\n";
	$odata=$odata."					<td align=\"center\" class=\"white\" width=\"20px\"><img src=\"../images/pixel.gif\"></td>\n";
	$odata=$odata."					<td align=\"left\" class=\"white\" width=\"300px\"><b>Filename</b></td>\n";
	$odata=$odata."					<td align=\"left\" class=\"white\" width=\"340px\"><b>Folder</b></td>\n";
	$odata=$odata."					<td align=\"center\" class=\"white\" width=\"100px\"><b>Added</b></td>\n";
	$odata=$odata."					<td align=\"center\" class=\"white\" width=\"25px\"><img src=\"../images/pixel.gif\"></td>\n";
	$odata=$odata."				</tr>\n";
	$odata=$odata."			</thead>\n";
	
	if ($nrow > 0)
	{
		$rcntFL=1;
		$ccnt=0;
		while ($row= mssql_fetch_array($res))
		{
			$ccnt++;
			if ($ccnt%2)
			{
				$tbgFL = 'even';
			}
			else
			{
				$tbgFL = 'odd';
			}
			
			$odata=$odata."				<tr class=\"".$tbgFL."\">\n";
			$odata=$odata."					<td align=\"right\">".$ccnt.".</td>\n";
			$odata=$odata."					<td align=\"left\">".trim($row['filename'])."</td>\n";
			$odata=$odata."					<td align=\"left\">". build_folder_chain($row['oid'],$row['fscid']) ."</td>\n";
			$odata=$odata."					<td align=\"center\">".date('m/d/Y',strtotime($row['adate']))."</td>\n";
			$odata=$odata."					<td align=\"right\"><a class=\"JMStooltip\" href=\"http://jms.bhnmi.com/export/fileout.php?docid=".$row['docid']."&storetype=file\" target=\"_new\" title=\"Click to Download\">".stubfileimages_tree($row['filetype'])."</a></td>\n";
			$odata=$odata."				</tr>\n";
		}
	}
	else
	{
		$odata=$odata."				<tr>\n";
		$odata=$odata."					<td align=\"right\"></td>\n";
		$odata=$odata."					<td align=\"left\">No Files Found!</td>\n";
		$odata=$odata."					<td align=\"center\"></td>\n";
		$odata=$odata."					<td align=\"center\"></td>\n";
		$odata=$odata."					<td align=\"left\"></td>\n";
		$odata=$odata."				</tr>\n";
	}
	
	$odata=$odata."			</table>\n";
	
	return $odata;
}

function FileEditControls($acclev,$n)
{
	$out='';
	
	if ($_REQUEST['oid']!=197 && $acclev >= 5)
	{
		$out=$out."<button class=\"ui-state-default ui-priority-primary ui-corner-bl JMStooltip\" title=\"Click to show Trash Can contents\"> Show Trash <img id=\"TrashCan\" src=\"../images/bin_empty.png\"></button>";
		$out=$out."<button class=\"ui-state-default ui-priority-primary JMStooltip FolderAdd\" id=\"".$n."\" title=\"Click to add a Folder to this Folder\"> Add Folder <img src=\"../images/folder_add.png\"></button>";
		$out=$out."<button class=\"ui-state-default ui-priority-primary ui-corner-br JMStooltip FileAdd\" id=\"".$n."\"> Add File <img src=\"../images/page_add.png\"></button>";
	}
	
	return $out;
}

function get_Permission_help($lvl)
{
	$out='';
	
	switch ($lvl)
	{
		case 0:
			$out='No Access';
			break;
		case 1:
			$out='Read Only';
			break;
		case 2:
			$out='Reserved';
			break;
		case 3:
			$out='Reserved';
			break;
		case 4:
			$out='Reserved';
			break;
		case 5:
			$out='Create & Read';
			break;
		case 6:
			$out='Create, Read, Update, & Delete';
			break;
		case 7:
			$out='Reserved';
			break;
		case 8:
			$out='Reserved';
			break;
		case 9:
			$out='Create, Read, Update, Delete, & Purge';
			break;
		default:
			$out='';
	}
	
	return $out;
}

function get_FileDetail_html($oid,$fscid,$acl)
{
	$dev_ar= array(2699999999999999999999999);
	$odata='';
	
	$qry = "
		select 
			docid,fscid,filename,filetype,adate
		from 
			jest..jestFileStore
		where
			fscid=".$_REQUEST['fscid']."
			and active=".$_REQUEST['shw_hidn']."
		order by
			filename asc
	";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);
	
	$qry1 = "
		select 
			C.fsid,C.fscid,C.fscatname,C.fctype,C.slevel,C.adate,C.parentid,
			(select count(fscid) from jest..jestFileStoreCategory where parentid=C.fscid) as flcnt,
			(select count(docid) from jest..jestFileStore where fscid=C.fscid) as ficnt
		from 
			jest..jestFileStoreCategory as C
		where
			C.oid=".$oid."
			and C.parentid=".$fscid."
			and C.active=".$_REQUEST['shw_hidn']."
		order by
			C.longstorage asc, C.fscatname asc
	";
	$res1 = mssql_query($qry1);
	$nrow1= mssql_num_rows($res1);
	
	$folderchain=build_folder_chain($oid,$fscid);
	//$showTrashBin=show_TrashBin_Icon($oid);
	$odata=$odata."			<input type=\"hidden\" id=\"ListingParentId\" value=\"".$fscid."\">\n";
	$odata=$odata."			<table class=\"transnb\">\n";
	$odata=$odata."			<thead>\n";
	$odata=$odata."				<tr>\n";
	$odata=$odata."					<td align=\"left\" colspan=\"2\" class=\"white\">\n";
	$odata=$odata.$folderchain;
	$odata=$odata."					</td>\n";
	$odata=$odata."					<td align=\"right\" colspan=\"4\" class=\"white\">".($nrow + $nrow1)." Item(s) Found</td>\n";
	$odata=$odata."				</tr>\n";
	
	if ($nrow > 0 or $nrow1 > 0)
	{
		$odata=$odata."				<tr>\n";
		$odata=$odata."					<td align=\"center\" class=\"white\" width=\"20px\"><img src=\"../images/pixel.gif\"></td>\n";
		$odata=$odata."					<td align=\"left\" class=\"white\" width=\"460px\"><b>Name</b></td>\n";
		$odata=$odata."					<td align=\"center\" class=\"white\" width=\"60px\"><b>Added</b></td>\n";
		$odata=$odata."					<td align=\"center\" class=\"white\" width=\"20px\"><img src=\"../images/pixel.gif\"></td>\n";
		$odata=$odata."					<td align=\"center\" class=\"white\" width=\"20px\"><img src=\"../images/pixel.gif\"></td>\n";
		$odata=$odata."					<td align=\"center\" class=\"white\" width=\"20px\"><img src=\"../images/pixel.gif\"></td>\n";
		$odata=$odata."				</tr>\n";
	}
	else
	{
		$odata=$odata."				<tr>\n";
		$odata=$odata."					<td align=\"center\" class=\"white\" width=\"20px\"><img src=\"../images/pixel.gif\"></td>\n";
		$odata=$odata."					<td align=\"center\" class=\"white\" width=\"460px\"><img src=\"../images/pixel.gif\"></td>\n";
		$odata=$odata."					<td align=\"center\" class=\"white\" width=\"60px\"><img src=\"../images/pixel.gif\"></td>\n";
		$odata=$odata."					<td align=\"center\" class=\"white\" width=\"20px\"><img src=\"../images/pixel.gif\"></td>\n";
		$odata=$odata."					<td align=\"center\" class=\"white\" width=\"20px\"><img src=\"../images/pixel.gif\"></td>\n";
		$odata=$odata."					<td align=\"center\" class=\"white\" width=\"20px\"><img src=\"../images/pixel.gif\"></td>\n";
		$odata=$odata."				</tr>\n";
	}
	
	$odata=$odata."			</thead>\n";
	
	$ccnt=0;
	
	if ($nrow1 > 0) //Folders
	{
		$rcntFL1=1;
		while ($row1= mssql_fetch_array($res1))
		{
			$ccnt++;
			if ($ccnt%2)
			{
				$tbgFL1 = 'even';
			}
			else
			{
				$tbgFL1 = 'odd';
			}
			
			$odata=$odata."				<tr class=\"".$tbgFL1."\">\n";
			
			if ((isset($oid) and $oid==$_SESSION['officeid']) and $acl >= 6)
			{
				$odata=$odata."					<td align=\"center\"><span class=\"FolderDrag ui-icon ui-icon-folder-collapsed\" id=\"".$row1['fscid']."\"></span></td>\n";
			}
			else
			{
				$odata=$odata."					<td align=\"center\"><span class=\"ui-icon ui-icon-folder-collapsed\"></span></td>\n";
			}
			
			$odata=$odata."					<td align=\"left\">".trim($row1['fscatname'])."</td>\n";
			$odata=$odata."					<td align=\"center\">".date('m/d/Y',strtotime($row1['adate']))."</td>\n";
			
			if ((isset($oid) and $oid==$_SESSION['officeid']) and $acl >= 6)
			{
				if ((isset($_REQUEST['shw_hidn']) and $_REQUEST['shw_hidn']==1) and $acl >= 6)
				{
					$odata=$odata."					<td align=\"left\"><button class=\"ui-state-default ui-priority-primary ui-corner-all ShowPermissions\" id=\"".$row1['fscid']."\" title=\"Folder Permissions\"><span class=\"ui-icon ui-icon-gear\"></span></button></td>\n";
				}
				else
				{
					$odata=$odata."					<td align=\"left\"><img src=\"../images/pixel.gif\"></td>\n";
				}
			}
			else
			{
				$odata=$odata."					<td align=\"left\"><img src=\"../images/pixel.gif\"></td>\n";
			}
			
			if ((isset($oid) and $oid==$_SESSION['officeid']) and $acl >= 6)
			{
				if (($row1['flcnt']==0 and $row1['ficnt']==0) and $acl >= 6)
				{
					if (isset($_REQUEST['shw_hidn']) and $_REQUEST['shw_hidn']==0)
					{
						$odata=$odata."					<td align=\"left\"><button class=\"ui-state-default ui-priority-primary ui-corner-all FolderPurge\" id=\"".$row1['fscid']."\" title=\"Purge Folder\"><div class=\"FolderParent\" id=\"".$row1['parentid']."\"></div><span class=\"ui-icon ui-icon-trash\"></span></button></td>\n";
					}
					else
					{
						$odata=$odata."					<td align=\"left\"><button class=\"ui-state-default ui-priority-primary ui-corner-all FolderDelete\" id=\"".$row1['fscid']."\" title=\"Delete Folder\"><div class=\"FolderParent\" id=\"".$row1['parentid']."\"></div><span class=\"ui-icon ui-icon-scissors\"></span></button></td>\n";
					}	
				}
				else
				{
					$odata=$odata."					<td align=\"left\"><img src=\"../images/pixel.gif\"></td>\n";
				}
			}
			else
			{
				$odata=$odata."					<td align=\"left\"><img src=\"../images/pixel.gif\"></td>\n";
			}
			
			if (isset($_REQUEST['shw_hidn']) and $_REQUEST['shw_hidn']==0)
			{
				$odata=$odata."					<td align=\"left\"><div class=\"FolderRestore\" id=\"".$row1['fscid']."\"><div class=\"FolderParent\" id=\"".$row1['parentid']."\"><input class=\"transnb\" type=\"image\" src=\"../images/folder_wrench.png\" title=\"Restore Folder\"></div></div></td>\n";
			}
			else
			{
				$odata=$odata."					<td align=\"right\"><button class=\"ui-state-default ui-priority-primary ui-corner-all FileListDetail JMStooltip\" id=\"".$row1['fscid']."\" title=\"Click to Open\"><span class=\"ui-icon ui-icon-folder-open\"></span></button></td>\n";
			}

			$odata=$odata."				</tr>\n";
		}
	}
	
	if ($nrow > 0) //Files
	{
		$rcntFL=1;
		while ($row= mssql_fetch_array($res))
		{
			$ccnt++;
			if ($ccnt%2)
			{
				$tbgFL = 'even';
			}
			else
			{
				$tbgFL = 'odd';
			}
			
			$odata=$odata."				<tr class=\"".$tbgFL."\">\n";
			
			if ((isset($oid) and $oid==$_SESSION['officeid']) and $acl >= 6)
			{
				$odata=$odata."					<td align=\"center\"><span class=\"FileDrag ui-icon ui-icon-document\" id=\"".$row['fscid']."\"></span></td>\n";
			}
			else
			{
				$odata=$odata."					<td align=\"center\"><span class=\"ui-icon ui-icon-document\"></span></td>\n";
			}
			
			$odata=$odata."					<td align=\"left\">".trim($row['filename'])."</td>\n";
			$odata=$odata."					<td align=\"center\">".date('m/d/Y',strtotime($row['adate']))."</td>\n";
			$odata=$odata."					<td align=\"left\"><img src=\"../images/pixel.gif\"></td>\n";
			
			if ((isset($oid) and $oid==$_SESSION['officeid']) and $acl >= 6)
			{
				$odata=$odata."					<td align=\"left\"><button class=\"ui-state-default ui-priority-primary ui-corner-all FileDelete\" title=\"Delete File\"><span class=\"ui-icon ui-icon-scissors\"></span></button></td>\n";
			}
			else
			{
				$odata=$odata."					<td align=\"left\"><img src=\"../images/pixel.gif\"></td>\n";
			}
			
			$odata=$odata."					<td align=\"right\"><a class=\"JMStooltip\" href=\"http://jms.bhnmi.com/export/fileout.php?docid=".$row['docid']."&storetype=file\" target=\"_new\" title=\"Click to Download\">".stubfileimages_tree($row['filetype'])."</a></td>\n";
			$odata=$odata."				</tr>\n";
		}
	}
	
	$odata=$odata."			</table>\n";
	
	//$odata=$odata.print_r($fl_ar);
	
	return $odata;
}

function get_FileDetail_json($oid,$acclev)
{
	$dev_ar= array(2699999999999999999999999);
	$err_data=array();
	$non_res_data=array();
	$fld_res_data=array();
	$fil_res_data=array();
	$out_data=array();
	
	$qry = "
		select 
			docid,fscid,filename,filetype,adate
		from 
			jest..jestFileStore
		where
			fscid=".$_REQUEST['fscid']."
			and active=".$_REQUEST['shw_hidn']."
		order by
			filename asc
	";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);
	
	$qry1 = "
		select 
			C.fsid,C.fscid,C.fscatname,C.fctype,C.slevel,C.adate,C.parentid,
			(select count(fscid) from jest..jestFileStoreCategory where parentid=C.fscid) as flcnt,
			(select count(docid) from jest..jestFileStore where fscid=C.fscid) as ficnt
		from 
			jest..jestFileStoreCategory as C
		where
			C.oid=".$_REQUEST['oid']."
			and C.parentid=".$_REQUEST['fscid']."
			and C.active=".$_REQUEST['shw_hidn']."
		order by
			C.longstorage asc, C.fscatname asc
	";
	$res1 = mssql_query($qry1);
	$nrow1= mssql_num_rows($res1);
	
	if ($nrow1 > 0) //Folders
	{
		while ($row1= mssql_fetch_array($res1))
		{
			$fld_res_data[]=array('fscid'=>$row1['fscid'],'fscatname'=>$row1['fscatname'],'fctype'=>$row1['fctype'],'slevel'=>$row1['slevel'],'adate'=>$row1['adate'],'parentid'=>$row1['parentid'],'flcnt'=>$row1['flcnt'],'ficnt'=>$row1['ficnt']);
		}
	}
	
	if ($nrow > 0) //Files
	{
		while ($row= mssql_fetch_array($res))
		{
			$fil_res_data[]=array('docid'=>$row['docid'],'fscid'=>$row['fscid'],'filename'=>$row['filename'],'filetype'=>$row['filetype'],'adate'=>$row['adate']);
		}
	}

	$non_res_data=array('Folders'=>$nrow1,'Files'=>$nrow,'ErrCnt'=>count($err_data),'ErrInfo'=>$err_data);
	//$out_data=array($fld_res_data,$fil_res_data,$non_res_data);
	//$out_data=$fld_res_data;
	//$odata=json_encode($out_data);
	//return $odata;
	return json_encode($fld_res_data);
}

function get_TrashBin($oid,$acl)
{
	$dev_ar= array(2699999999999999999999999);
	$odata='';
	
	$qry = "
		select 
			F.docid,F.fscid,F.fscid as parentid,F.filename,F.filetype,F.adate,(select fscatname from jest..jestFileStoreCategory where fscid=F.fscid) as fscatname
		from 
			jest..jestFileStore as F
		where
			F.oid = ".$oid."
			and F.active = 0
			and F.slevel <= ".$acl."
			
		order by
			F.filename asc
	";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);
	
	$qry1 = "
		select 
			C.fsid,C.fscid,C.fscatname,C.fctype,C.slevel,C.adate,C.parentid,(select fscatname from jest..jestFileStoreCategory where fscid=C.parentid) as pfscatname
		from 
			jest..jestFileStoreCategory as C
		where
			C.oid=".$oid."
			and C.active=0
			and slevel <= ".$acl."
		order by
			C.fscatname asc
	";
	$res1 = mssql_query($qry1);
	$nrow1= mssql_num_rows($res1);
	
	$odata=$odata."			<table class=\"transnb\">\n";
	$odata=$odata."			<thead>\n";
	$odata=$odata."				<tr>\n";
	$odata=$odata."					<td align=\"left\" colspan=\"2\" class=\"white\">Viewing Trash Bin</td>\n";
	$odata=$odata."					<td align=\"right\" colspan=\"4\" class=\"white\">".($nrow + $nrow1)." Item(s) Found</td>\n";
	$odata=$odata."				</tr>\n";
	
	if ($nrow > 0 or $nrow1 > 0)
	{
		$odata=$odata."				<tr>\n";
		$odata=$odata."					<td align=\"center\" class=\"white\" width=\"20px\"><img src=\"../images/pixel.gif\"></td>\n";
		$odata=$odata."					<td align=\"left\" class=\"white\" width=\"300px\"><b>Name</b></td>\n";
		$odata=$odata."					<td align=\"left\" class=\"white\" width=\"180px\"><b>Parent</b></td>\n";
		$odata=$odata."					<td align=\"center\" class=\"white\" width=\"60px\"><b>Added</b></td>\n";
		$odata=$odata."					<td align=\"center\" class=\"white\" width=\"20px\"><img src=\"../images/pixel.gif\"></td>\n";
		$odata=$odata."					<td align=\"center\" class=\"white\" width=\"20px\"><button class=\"ui-state-default ui-priority-primary ui-corner-all JMStooltip\" id=\"TrashBinPurge\" title=\"Click this icon to Purge the Trash Bin\"><span class=\"ui-icon ui-icon-trash\"></span></button></td>\n";
		$odata=$odata."				</tr>\n";
	}
	
	$odata=$odata."			</thead>\n";
	
	$ccnt=0;
	
	if ($nrow1 > 0) //Folders
	{
		$rcntFL1=1;
		while ($row1= mssql_fetch_array($res1))
		{
			$ccnt++;
			if ($ccnt%2)
			{
				$tbgFL1 = 'even';
			}
			else
			{
				$tbgFL1 = 'odd';
			}
			
			$odata=$odata."				<tr class=\"".$tbgFL1."\">\n";
			$odata=$odata."					<td align=\"center\"><span class=\"FolderDrag ui-icon ui-icon-folder-collapsed\" id=\"".$row1['fscid']."\"></span></td>\n";
			$odata=$odata."					<td align=\"left\">".trim($row1['fscatname'])."</td>\n";
			$odata=$odata."					<td align=\"left\">\n";
			
			if ($row1['parentid'] == 0)
			{
				$odata=$odata."HOME";
			}
			else
			{
				$odata=$odata.$row1['pfscatname'];
			}
			
			$odata=$odata."					</td>\n";
			$odata=$odata."					<td align=\"center\">".date('m/d/Y',strtotime($row1['adate']))."</td>\n";
			$odata=$odata."					<td align=\"left\"><button class=\"ui-state-default ui-priority-primary ui-corner-all FolderPurge\" id=\"".$row1['fscid']."\" title=\"Purge Folder\"><div class=\"FolderParent\" id=\"".$row1['parentid']."\"><span class=\"ui-icon ui-icon-close\"></span></button></td>\n";
			$odata=$odata."					<td align=\"left\"><button class=\"ui-state-default ui-priority-primary ui-corner-all FolderRestore\" id=\"".$row1['fscid']."\" title=\"Restore Folder\"><div class=\"FolderParent\" id=\"".$row1['parentid']."\"><span class=\"ui-icon ui-icon-plus\"></span></button></td>\n";
			$odata=$odata."				</tr>\n";
		}
	}
	
	if ($nrow > 0) //Files
	{
		$rcntFL=1;
		while ($row= mssql_fetch_array($res))
		{
			$ccnt++;
			if ($ccnt%2)
			{
				$tbgFL = 'even';
			}
			else
			{
				$tbgFL = 'odd';
			}
			
			$odata=$odata."				<tr class=\"".$tbgFL."\">\n";
			$odata=$odata."					<td align=\"center\"><span class=\"FileDrag ui-icon ui-icon-document\" id=\"".$row['fscid']."\"></span></td>\n";
			$odata=$odata."					<td align=\"left\">".trim($row['filename'])."</td>\n";
			$odata=$odata."					<td align=\"left\">".$row['fscatname']."</td>\n";
			$odata=$odata."					<td align=\"center\">".date('m/d/Y',strtotime($row['adate']))."</td>\n";
			$odata=$odata."					<td align=\"left\"><button class=\"ui-state-default ui-priority-primary ui-corner-all FilePurge\" id=\"".$row['fscid']."\" title=\"Purge File\"><div class=\"FolderParent\" id=\"".$row['parentid']."\"><span class=\"ui-icon ui-icon-close\"></span></button></td>\n";
			$odata=$odata."					<td align=\"left\"><button class=\"ui-state-default ui-priority-primary ui-corner-all FileRestore\" id=\"".$row['fscid']."\" title=\"Restore File\"><div class=\"FolderParent\" id=\"".$row['parentid']."\"><span class=\"ui-icon ui-icon-plus\"></span></button></td>\n";
			$odata=$odata."				</tr>\n";
		}
	}
	
	$odata=$odata."			</table>\n";
	
	//$odata=$odata.print_r($fl_ar);
	
	return $odata;
}

function purge_TrashBin($oid)
{
	$odata='';
	
	$qry = "
		select 
			F.docid
		from 
			jest..jestFileStore as F
		where
			F.oid = ".$oid."
			and F.active = 0
			
		order by
			F.filename asc
	";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);
	
	$qry1 = "
		select 
			C.fsid
		from 
			jest..jestFileStoreCategory as C
		where
			C.oid=".$oid."
			and C.active=0
		order by
			C.fscatname asc
	";
	$res1 = mssql_query($qry1);
	$nrow1= mssql_num_rows($res1);
	
	echo 'Items Found: ' . ($nrow + $nrow1);
	
	return $odata;
}

function delete_Folder($oid,$fscid,$pfscid,$acclev)
{
	//$out='Deleted!<br>'.$oid.':'.$fpid.':'.$pfpid.':'.$acclev.'<br><br>';
	
	$err_cnt=0;
	$err_txt='';
	if (isset($fscid) && $fscid!=0)
	{
		//echo 'Deactivating File...';
		$qry0 = "SELECT C.fscid,C.parentid,C.fscatname from jest..jestFileStoreCategory as C where C.fscid=".$fscid.";";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);
		
		if ($nrow0 > 0)
		{
			$row0= mssql_fetch_array($res0);
			
			$qry0a = "SELECT docid from jest..jestFileStore where fscid=".$row0['fscid'].";";
			$res0a = mssql_query($qry0a);
			$nrow0a= mssql_num_rows($res0a);
			
			//echo $nrow0a.'<br>';
			//echo $nrow0b.'<br>';
			
			if ($nrow0a==0)
			{
				$qry1 = "UPDATE jest..jestFileStoreCategory SET active=0,udate=getdate() WHERE fscid=".$row0['fscid'].";";
				$res1 = mssql_query($qry1);
				//$err_txt=$err_txt.$qry1.'<br>';
				
				$err_txt=$err_txt.$row0['fscatname'].' Deleted<br>';
			}
			else
			{
				$err_txt=$err_txt.'Folder not Empty ('.$fscid.')<br>';
				$err_cnt++;
			}
		}
		else
		{
			$err_txt=$err_txt.'Folder ID not Found ('.$fscid.')<br>';
			$err_cnt++;
		}
	}
	else
	{
		$err_txt=$err_txt.'Invalid Folder ID ('.$fscid.')<br>';
		$err_cnt++;
	}
	
	return $err_txt;
}

function restore_Folder($oid,$fscid,$pfscid,$acclev)
{
	//$out='Deleted!<br>'.$oid.':'.$fpid.':'.$pfpid.':'.$acclev.'<br><br>';
	
	$err_cnt=0;
	$err_txt='';
	if (isset($fscid) && $fscid!=0)
	{
		//echo 'Deactivating File...';
		$qry0 = "SELECT C.fscid,C.parentid,C.fscatname from jest..jestFileStoreCategory as C where C.fscid=".$fscid.";";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);
		
		if ($nrow0 > 0)
		{
			$row0= mssql_fetch_array($res0);
			
			$qry0a = "SELECT docid from jest..jestFileStore where fscid=".$row0['fscid'].";";
			$res0a = mssql_query($qry0a);
			$nrow0a= mssql_num_rows($res0a);
			
			//echo $nrow0a.'<br>';
			//echo $nrow0b.'<br>';
			
			if ($nrow0a==0)
			{
				$qry1 = "UPDATE jest..jestFileStoreCategory SET active=1,udate=getdate() WHERE fscid=".$row0['fscid'].";";
				$res1 = mssql_query($qry1);
				//$err_txt=$err_txt.$qry1.'<br>';
				
				$err_txt=$err_txt.$row0['fscatname'].' Restored<br>';
			}
			else
			{
				$err_txt=$err_txt.'Folder not Empty ('.$fscid.')<br>';
				$err_cnt++;
			}
		}
		else
		{
			$err_txt=$err_txt.'Folder ID not Found ('.$fscid.')<br>';
			$err_cnt++;
		}
	}
	else
	{
		$err_txt=$err_txt.'Invalid Folder ID ('.$fscid.')<br>';
		$err_cnt++;
	}
	
	return $err_txt;
}

?>