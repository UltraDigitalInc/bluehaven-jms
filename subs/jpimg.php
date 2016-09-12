<?php
require_once('../jpgraph/jpgraph.php'); 
require_once('../jpgraph/jpgraph_line.php');
$data=array();

//print_r($_GET);

foreach ($_GET as $n => $v)
{
   if (is_numeric($n) && $n >= 1)
   {
      //$data['calls'][]=$v;
      $data[]=$v;
      //echo "HIT<br>";
   }
}

//print_r($data);
//$data['calls'] = array(33,22,12,0,44,67,32);

$graph = new graph(400, 200); 
$graph->img->SetMargin(25, 25, 25, 25);     
$graph->SetScale('textlin');
//$graph->title->Set('IVR Calls '.$_GET['tfn'].' '.date("m/d/y",time()));
$line1 = new LinePlot($data); 
$line1->SetColor('darkblue'); 
$graph->Add($line1); 
$graph->Stroke();

?>