<?php
require_once('../jpgraph/jpgraph.php');
require_once('../jpgraph/jpgraph_antispam.php');

session_start();
$spam = new  AntiSpam();
$spam-> Set($_SESSION['war']);

if($spam->Stroke () === false)
{ 
   die("Illegal or no data to plot"); 
}

?>