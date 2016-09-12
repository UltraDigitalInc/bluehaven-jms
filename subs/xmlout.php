<?php
session_start();

if (isset($_SESSION['officeid']))
{
    if (isset($_REQUEST['data']))
    {
        //header("Content-type: text/XML");
        echo "<xml version=\"1.0\" encoding=\"UTF-8\">";
        echo $_REQUEST['data'];
        echo "</xml>";
    }
}

?>