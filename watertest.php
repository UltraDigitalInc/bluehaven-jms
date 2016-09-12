<?php


function watermarkImage ($SourceFile, $WaterMarkText, $DestinationFile) {
   
   list($width, $height) = getimagesize($SourceFile);
   $image_p = imagecreatetruecolor($width, $height);
   $image = imagecreatefromjpeg($SourceFile);
   
   imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height);
   
   $fcolor = imagecolorallocate($image_p, 255, 255, 255);
   $font = 'arial.ttf';
   $font_size = 10;
   
   imagettftext($image_p, $font_size, 0, 10, 20, $fcolor, $font, $WaterMarkText);
   
   if ($DestinationFile<>'')
   {
      imagejpeg ($image_p, $DestinationFile, 100); 
   }
   else
   {
      header('Content-Type: image/jpeg');
      imagejpeg($image_p, null, 100);
   };
   
   imagedestroy($image); 
   imagedestroy($image_p); 
};

watermarkImage ('images/waterm_test.jpg', "Copyright ".date('Y')." Blue Haven Pools & Spas", $DestinationFile)

//echo var_dump(getimagesize('images/pixel.gif'));
?>