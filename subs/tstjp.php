<?php
require_once('../jpgraph/jpgraph.php'); 
require_once('../jpgraph/jpgraph_line.php');
            
$tfn='800-555-1212';
            
$data['calls'] = array (0,33,22,4,15,18,9,0,5,32,13,41,23,15,4); 
$graph = new graph(500, 200); 
$graph->img->SetMargin(25, 25, 25, 25);     
$graph->SetScale('textlin');
$graph->title->Set('IVR Calls '.$tfn.' '.date("m/d/y",time()));
$line1 = new LinePlot($data['calls']); 
$line1->SetColor('darkblue'); 
$graph->Add($line1); 
$graph->Stroke();
?>