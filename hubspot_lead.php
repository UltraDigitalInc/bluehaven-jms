<?php

$_POST = file_get_contents('php://input');
$myFile = "hubspot_testFile.txt";
$fh = fopen($myFile, 'w') or die("can't open file");

fwrite($fh, $_POST);
fwrite($fh, $_GET);
fwrite($fh, $_REQUEST);
fwrite($fh, $_POST['message']);
fwrite($fh, $_GET['message']);

fclose($fh);


?>