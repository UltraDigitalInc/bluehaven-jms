<?php

function basematrix()
{
	if ($_SESSION['securityid']==332 or $_SESSION['securityid']==26)
	{
		echo "<script type=\"text/javascript\" src=\"js/jquery_comm_builder_func.js\"></script>\n";
		echo "<script type=\"text/javascript\" src=\"js/jquery_comm_builder_help.js\"></script>\n";
	}
	else
	{
		echo 'Commission Builder Offline for Maintenance';
	}
}

?>