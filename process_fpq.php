<?php

error_reporting(E_ALL);
//ini_set('display_errors','On');

include('./connect_db.php');
include('./emailroutines_func.php');

$lc=112;
$p=true;
$t=false;

if ($lc==112)
{
    include('./templates/freepoolquotes_temp.php');
}

getleadmail($lc,$p,$t);

autosort();

?>