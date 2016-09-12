<?php

header("Content-type: text/xml");
header("Content-Disposition: inline");
header("Pragma: no-cache");
header("Expires: 0");

echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
echo "<!DOCTYPE ivr_info SYSTEM \"http://www.switchvox.com/xml/ivr.dtd\">";
echo "<ivr_info>";
echo "<variable name=\"ivrcnt\">".strlen($_GET['ivrcnt'])."</variable>";
echo "</ivr_info>";

?>