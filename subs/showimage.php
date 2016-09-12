<?php

session_start();

ini_set('display_errors','On');
error_reporting(E_ALL);

function watermarkImage ($SourceFile, $WaterMarkText) {
   
   list($width, $height) = getimagesize($SourceFile);
   $image_p = imagecreatetruecolor($width, $height);
   $image = imagecreatefromjpeg($SourceFile);
   
   imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height);
   
   $fcolor = imagecolorallocate($image_p, 255, 255, 255);
   $font = 'arial.ttf';
   $font_size = 10;
   
   imagettftext($image_p, $font_size, 0, 10, 20, $fcolor, $font, $WaterMarkText);

   header('Content-Type: image/jpeg');
   imagejpeg($image_p, null, 100);
   
   imagedestroy($image); 
   imagedestroy($image_p); 
};

if (!isset($_SESSION['securityid']) && !isset($_REQUEST['docid']))
{
   echo 'System Error.';
   exit;
}
else
{
   include ('../connect_db.php');
   
   $qry = "SELECT docid,filename,filestore,fsfilename from jestFileStore where docid =".$_REQUEST['docid'].";";
   $res = mssql_query($qry);
   $row = mssql_fetch_array($res);
   $nrow= mssql_num_rows($res);
   
   if ($nrow > 0)
   {
	  echo "<img src=\"../export/fileout.php?storetype=file&docid=".$row['docid']."\" height=\"480px\" width=\"640px\" title=\"".$row['filename']."\">\n";
	  
	  //watermarkImage (addslashes(FILESTORE.$row['filestore'].$row['fsfilename']), "Copyright ".$date('Y')." Blue Haven Pools & Spas");
   }
   else
   {
	  echo 'No File';
   }
}

?>