<?php

session_start();

if (!isset($_SESSION['ifcid']) || !is_numeric($_SESSION['ifcid']))
{
	exit;
}
	
function listcomments_net()
{
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	
	//print_r($_SESSION);
	
	include('../connect_db.php');

	$qryL = "SELECT
				c.*
				,(select substring(fname,1,1) + ' ' + lname from jest..security where securityid=c.sid) as fsid
			FROM
				chistory_net AS c WHERE c.cnid='".$_SESSION['ifcid']."' ORDER BY c.mdate DESC;";
	$resL = mssql_query($qryL);
	$nrowL= mssql_num_rows($resL);
	
	echo "<html>\n";
	echo "<head>\n";
	
	?>
	
	<link rel="stylesheet" type="text/css" href="../yui/build/reset-fonts-grids/reset-fonts-grids.css">
			
	<?php
	
	echo "   <link rel=\"stylesheet\" type=\"text/css\" href=\"../bh_main_iframe.css\" />\n";
	echo "</head>\n";
	echo "   <body bgcolor=\"#F5F5F5\">\n";

    if ($nrowL > 0)
    {
        echo "<table align=\"center\" width=\"100%\">\n";
        echo "   <tr class=\"tblhd\">\n";
        echo "      <td align=\"left\" width=\"100px\"><b>Date</b></td>\n";
        echo "      <td align=\"left\" width=\"75px\"><b>Name</b></td>\n";
        echo "      <td align=\"left\"><b>Comments</b></td>\n";
        echo "   </tr>\n";
    
        $cmntcnt=0;
        while ($rowL = mssql_fetch_array($resL))
        {
            $cmntcnt++;
			if ($cmntcnt%2)
			{
				$cmt_tbg="even";
			}
			else
			{
				$cmt_tbg="odd";
			}

            $stage='';
    
            echo "   <tr class=\"".$cmt_tbg."\">\n";
            echo "      <td align=\"left\" valign=\"top\">".date('m/d/y g:i A',strtotime($rowL['mdate']))."</td>\n";
			echo "      <td align=\"left\" valign=\"top\">".$rowL['fsid']."</td>\n";
            echo "      <td align=\"left\">\n";
    
            echo htmlspecialchars_decode($rowL['mtext']);
    
            echo "		</td>\n";
            echo "   </tr>\n";
        }
    
        echo "</table>\n";
    }
	else
	{
		echo "No Comments Found";
	}
    
	echo "   </body>\n";
	echo "</html>\n";
}

function deletecmnt_net()
{
	include('../connect_db.php');
	$qry = "DELETE FROM chistory_net WHERE chid='".$_REQUEST['chid']."';";
	$res = mssql_query($qry);
}

//Main
if (isset($_REQUEST['call']) && $_REQUEST['call']=='deletecmnt_net')
{
	deletecmnt_net();
	listcomments_net();
}
else
{
	listcomments_net();
}

?>