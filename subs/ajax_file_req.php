<?php
session_start();

ini_set('display_errors','On');
error_reporting(E_ALL);
//echo 'START<br>';

//display_array($_REQUEST);

$data='';
if (isset($_SESSION['securityid']) and $_SESSION['securityid']!=0)
{
	//echo 'SEC<br>';
	include ('../connect_db.php');
	include ('./ajax_common_func.php');
	include ('./ajax_file_func.php');
	//echo 'INC<br>';
	
	$qryp0 = "select O.officeid,O.name,O.fsshared from offices as O where O.officeid=".$_SESSION['officeid'].";";
	$resp0 = mssql_query($qryp0);
	$rowp0 = mssql_fetch_array($resp0);
	
	//echo $qryp0.'<br>';
	
	// View Only mode for Corporate Shared Files
	$qry0 = "select S.securityid,S.officeid,S.filestoreaccess from security as S where S.securityid=".$_SESSION['securityid'].";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	$nrow0= mssql_num_rows($res0);
	
	if (!isTimeout())
	{
		if ((isset($rowp0['fsshared']) and $rowp0['fsshared']==1))
		{
			//echo 'Shared/Office<br>';
			if ($row0['filestoreaccess'] >= 5)
			{
				if (isset($_REQUEST['call']) and $_REQUEST['call']=='file')
				{
					//echo 'CALL<br>';
					if (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_FolderTree_list')
					{
						//echo 'list<br>';
						(isset($_REQUEST['shwhid']) and $_REQUEST['shwhid']=='h') ? $shwhid=0 : $shwhid=1;
						$data=get_FolderTree_list($_REQUEST['oid'],$row0['filestoreaccess'],$shwhid);
					}
					elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_FileDetail_html')
					{
						//echo 'list<br>';
						$data=get_FileDetail_html($_REQUEST['oid'],$_REQUEST['fscid'],$row0['filestoreaccess']);
					}
					elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_FileDetail_json')
					{
						//echo 'list<br>';
						$data=get_FileDetail_json($_REQUEST['oid'],$row0['filestoreaccess']);
					}
					elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_FileSearch_list')
					{
						//echo 'list<br>';
						$data=get_FileSearch_list($_REQUEST['oid'],$row0['filestoreaccess']);
					}
					elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_TrashBin')
					{
						//echo 'list<br>';
						$data=get_TrashBin($_REQUEST['oid'],$row0['filestoreaccess']);
					}
					elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='purge_TrashBin')
					{
						//echo 'list<br>';
						$data=purge_TrashBin($_REQUEST['oid']);
					}
					elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_Permissions')
					{
						//echo 'list<br>';
						$data=get_Permissions($_REQUEST['oid'],$_REQUEST['fscid'],$row0['filestoreaccess']);
					}
					elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='delete_Folder')
					{
						//echo 'list<br>';
						$data=delete_Folder($_REQUEST['oid'],$_REQUEST['fscid'],$_REQUEST['pfscid'],$row0['filestoreaccess']);
					}
					elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='restore_folder')
					{
						//echo 'list<br>';
						$data=restore_folder($_REQUEST['oid'],$_REQUEST['fscid'],$_REQUEST['pfscid'],$row0['filestoreaccess']);
					}
					elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='update_Folder_security')
					{
						//echo 'list<br>';
						$data=update_Folder_security($_REQUEST['oid'],$_REQUEST['fscid'],$_REQUEST['new_sec']);
					}
					elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='add_File')
					{
						//echo 'list add_file<br>';
						//$data=display_array($_REQUEST);
						$data=add_File($_REQUEST['oid'],$_REQUEST['parentid'],$_REQUEST['nfuserfile'],$_REQUEST['nfuid'],$_REQUEST['nfstoretype'],$_REQUEST['MAX_FILE_SIZE']);
					}
					elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='add_Folder')
					{
						//echo 'list<br>';
						$data=add_Folder($_REQUEST['oid'],$_REQUEST['parentid'],$_REQUEST['foldername']);
					}
					elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='show_FileStoreTree')
					{
						$data=show_FileStoreTree($_REQUEST['oid'],$row0['filestoreaccess']);
					}
					elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='show_FileStoreTreeJSON')
					{
						//$data='<pre>'.show_FileStoreTreeJSON($_REQUEST['oid'],$row0['filestoreaccess']).'</pre>';
						$data=show_FileStoreTreeJSON($_REQUEST['oid'],$row0['filestoreaccess']);
					}
					elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='show_FileStoreSearchResult')
					{
						//echo 'list<br>';
						(isset($_REQUEST['shwhid']) and $_REQUEST['shwhid']=='h') ? $shwhid=0 : $shwhid=1;
						//$data=show_FileStoreSearchResult($_REQUEST['oid'],$row0['filestoreaccess'],$shwhid);
						$data=get_FileSearch_list($_REQUEST['oid'],$row0['filestoreaccess']);
					}
					elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='show_FileStoreCapacity')
					{
						$data=show_FileStoreCapacity($_REQUEST['oid'],$row0['filestoreaccess']);
					}
					elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='show_FilestoreTree')
					{
						//echo 'list<br>';
						(isset($_REQUEST['shwhid']) and $_REQUEST['shwhid']=='h') ? $shwhid=0 : $shwhid=1;
						$data=show_FilestoreTree($_REQUEST['oid'],$row0['filestoreaccess'],$shwhid);
					}
					elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_FSInfo')
					{
						$data=get_FSInfo($_REQUEST['oid'],$_REQUEST['fldpid']);
					}
					elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='show_FileStoreTreeHTML')
					{
						//$data='TEST';
						$data=show_FileStoreTreeHTML($_REQUEST['oid'],$row0['filestoreaccess']);
					}
					elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='show_FileStoreListHTML')
					{
						//$data='TEST';
						$data=show_FileStoreListHTML($_REQUEST['oid'],$row0['filestoreaccess']);
					}
					elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='folder_maintenance')
					{
						//$data='TEST';
						$data=folder_maintenance($_REQUEST['oid'],$_REQUEST['nfscid']);
					}
					elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='file_maintenance')
					{
						//$data='TEST';
						$data=file_maintenance($_REQUEST['oid'],$_REQUEST['docid']);
					}
					elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='upload_file')
					{
						//$data='TEST';
						$data=upload_file($_REQUEST['oid'],$_REQUEST['docid']);
					}
				}
				else
				{
					$data=$data."Malformed Request (" . __LINE__ . ")<br>";
					$data=$data."Debug:<br> " . print_r($_REQUEST) . "";
				}
			}
			else
			{
				$data=$data."You do not have appropriate Access to view this resource (" . __LINE__ . ")";
			}
		}
		else
		{
			$data=$data."You do not have appropriate Access to view this resource (" . __LINE__ . ")";
		}
	}
	else
	{
		$data=$data."Connection Time Out. You must log again to clear this condition (" . __LINE__ . ")";
	}
	
}
else
{
	$data=$data."Unauthorized (" . __LINE__ . ")";
}

if (isset($_REQUEST['optype']) and $_REQUEST['optype']=='json')
{
	echo json_encode($data);
}
else
{
	echo $data;
}

//display_array($_REQUEST);

//echo 'END<br>';

?>