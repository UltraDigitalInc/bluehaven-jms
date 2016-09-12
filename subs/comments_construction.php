<?php

session_start();

if (!isset($_SESSION['constr_cid']) || !is_numeric($_SESSION['constr_cid']))
{
	exit;
}
	
function listcomments_constr()
{
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	
	//print_r($_SESSION);
	
	include('../connect_db.php');

	$qryL = "SELECT
				c.*
				,(select lname + ' ' + substring(fname,1,1) from jest..security where securityid=c.sid) as fsid
			FROM
				construction_comments AS c WHERE c.cid='".$_SESSION['constr_cid']."' ORDER BY c.mdate DESC;";
	$resL = mssql_query($qryL);
	$nrowL= mssql_num_rows($resL);
	
	echo "<html>\n";
	echo "<head>\n";
	
	?>
	
	<link rel="stylesheet" type="text/css" href="../yui/build/reset-fonts-grids/reset-fonts-grids.css">
			
	<?php
	
	echo "   <link rel=\"stylesheet\" type=\"text/css\" href=\"../bh_embed_new.css\" />\n";
	echo "</head>\n";
	echo "   <body bgcolor=\"#B9D3EE\">\n";
	
	?>
	
	<script type="text/javascript">

	function ConfirmDeleteComment()
	{
		var agree = confirm('You are are attempting to Delete this Comment\n\nClick OK to continue or CANCEL stop the Delete process');
	
		if (agree)
		{
			return true;
		}
		
		return false;
	}
	
	</script>
	
	<?php

    if ($nrowL > 0)
    {
        echo "<table align=\"center\" width=\"100%\">\n";
        echo "   <tr>\n";
        echo "      <td align=\"left\" width=\"80px\"><b>Date</b></td>\n";
        echo "      <td align=\"left\" width=\"75px\"><b>Name</b></td>\n";
        echo "      <td align=\"left\" width=\"210px\"><b>Comment</b></td>\n";
        echo "      <td align=\"center\" width=\"20px\"><img src=\"../images/pixel.gif\"></td>\n";
        echo "   </tr>\n";
    
        $cmntcnt=0;
        while ($rowL = mssql_fetch_array($resL))
        {
            $cmntcnt++;
			if ($cmntcnt%2)
			{
				$cmt_tbg="white";
			}
			else
			{
				$cmt_tbg="ltgray";
			}

            $stage='';
    
            echo "   <tr>\n";
            echo "      <td align=\"left\" valign=\"top\" class=\"".$cmt_tbg."\">".date('m/d/y g:iA',strtotime($rowL['mdate']))."</td>\n";
			echo "      <td align=\"left\" valign=\"top\" class=\"".$cmt_tbg."\">".$rowL['fsid']."</td>\n";
            echo "      <td align=\"left\" class=\"".$cmt_tbg."\">\n";
    
            echo htmlspecialchars_decode($rowL['mtext']);
    
            echo "		</td>\n";
            echo "      <td align=\"center\" valign=\"top\" width=\"20px\" class=\"".$cmt_tbg."\">\n";
            
            if (isset($_SESSION['securityid']) && ($_SESSION['securityid']==26 ||$_SESSION['securityid']==332))
            {
				echo "		<form method=\"post\">\n";
				echo "		<input type=\"hidden\" name=\"action\" value=\"jobs\">\n";
				echo "		<input type=\"hidden\" name=\"call\" value=\"deletecmnt_constr\">\n";
				echo "		<input type=\"hidden\" name=\"ccid\" value=\"".$rowL['ccid']."\">\n";
				//echo "		<input class=\"transnb\" type=\"image\" src=\"../images/action_delete.gif\" alt=\"Delete Comment\" onClick=\"return ConfirmDeleteComment();\">\n";
				echo "		</form>\n";
            }
            else
            {
                echo "<img src=\"../images/pixel.gif\">";
            }
            
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

function deletecmnt_constr()
{
	include('../connect_db.php');
	$qry = "DELETE FROM construction_comments WHERE ccid='".$_REQUEST['ccid']."';";
	$res = mssql_query($qry);
}

//Main
if (isset($_REQUEST['call']) && $_REQUEST['call']=='deletecmnt_constr')
{
	deletecmnt_constr();
	listcomments_constr();
}
else
{
	listcomments_constr();
}

?>