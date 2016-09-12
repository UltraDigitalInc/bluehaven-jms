<?php

include (".\connect_db.php");
include (".\calc_func.php");
//include (".\display_func.php");
include (".\xml_func.php");


if (getenv("REMOTE_ADDR")=="192.168.1.97"||getenv("REMOTE_ADDR")=="192.168.1.21")
{
	XML_poll();
}
else 
{
	declare_XML("1.0","ISO-8859-1");
	single_element("ERROR","Unqual HOST");
}

?>