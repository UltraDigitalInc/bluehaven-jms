<?php

header("Cache-Control: no-cache, must-revalidate");

error_reporting(E_ALL);

//print_r($_REQUEST);
//exit;

function clookup($ani)
{
    $qry0 = "
    declare @phnum varchar(10)
    declare @ephnum varchar(12)
    set @phnum='".$ani."'
    set @ephnum=substring(@phnum,1,3) + '-' + substring(@phnum,4,3) +  '-' + substring(@phnum,7,4)
    
    select
         C.cid
        ,C.officeid
        ,(select name from offices where officeid=C.officeid) as oname
        ,C.securityid
        ,C.clname
        ,C.cfname
        ,C.chome
        ,C.cwork
        ,C.ccell
        ,C.caddr1
        ,C.czip1
        ,C.saddr1
        ,C.szip1
        ,C.dupe
        ,C.hidden
        --,(select top 1 contractdate from jest..jdetail where officeid=C.officeid and njobid=C.njobid and jadd=0) as condate
        --,(select top 1 digdate from jest..jobs where officeid=C.officeid and njobid=C.njobid) as digdate
    from 
    	jest..cinfo as C
    where
    	chome = @phnum
    	or cwork = @phnum
    	or ccell = @phnum
    	or chome = @ephnum
    	or cwork = @ephnum
    	or ccell = @ephnum
    order by 
    	oname asc,
    	clname asc;
        ";
	$res0 = mssql_query($qry0);
	//$row0 = mssql_fetch_array($res0);
    $nrow0= mssql_num_rows($res0);
    
    //echo $qry0.'<br>';
    //echo $nrow0.'<br>';
    
    if ($nrow0==0)
    {
        echo 'No Records found for: '.$ani.'<br>';
    }
    else
    {
        echo "<table border=0 align=\"left\" width=\"90%\">\n";
		echo "  <tr>\n";
		echo "      <td>\n";
		echo "          <table width=\"100%\">\n";
		echo "              <tr>\n";
		echo "                  <td align=\"left\">\n";
		echo "                      <table class=\"title\" width=\"100%\">\n";
		echo "                          <tr>\n";
		echo "                              <td class=\"ltgray_und\" align=\"left\"><b>BH Customer Search Results:</b> ". $ani ." </td>\n";
		echo "                              <td class=\"ltgray_und\" align=\"right\"><b>Record(s): <font color=\"blue\">".$nrow0."</font></b></td>\n";
		echo "                          </tr>\n";
		echo "                      </table>\n";
		echo "                  </td>\n";
		echo "              </tr>\n";
		echo "              <tr>\n";
		echo "                  <td align=\"left\">\n";
		echo "                      <table width=\"100%\" border=1>\n";
		echo "                          <tr>\n";
		echo "                              <th align=\"center\"></th>\n";
        echo "                              <th align=\"left\"><b>Office</b></th>\n";
		echo "                              <th align=\"left\"><b>Customer</b></th>\n";
		echo "                              <th align=\"center\"><b>Home Ph</b></th>\n";
		echo "                              <th align=\"center\"><b>Work Ph</b></th>\n";
        echo "                              <th align=\"center\"><b>Cell Ph</b></th>\n";
        echo "                              <th align=\"left\"><b>Pool Addr</b></th>\n";
        echo "                              <th align=\"center\"><b>Pool Zip</b></th>\n";
        //echo "                              <th align=\"center\"><b>Contract Date</b></th>\n";
        //echo "                              <th align=\"center\"><b>Dig Date</b></th>\n";
        echo "                              <th align=\"center\"></th>\n";
		echo "                          </tr>\n";
        
        $ccnt=1;
        while ($row0 = mssql_fetch_array($res0))
        {
            $uid = md5(time().$row0['cid']).".PBX";
            echo "                          <tr>\n";
            echo "                              <td align=\"right\">".$ccnt++.".</td>\n";
            echo "                              <td align=\"left\">".$row0['oname']."</td>\n";
            echo "                              <td align=\"left\">".$row0['clname'].", ".$row0['cfname']."</td>\n";
            echo "                              <td align=\"center\">".$row0['chome']."</td>\n";
            echo "                              <td align=\"center\">".$row0['cwork']."</td>\n";
            echo "                              <td align=\"center\">".$row0['ccell']."</td>\n";
            echo "                              <td align=\"left\">".$row0['saddr1']."</td>\n";
            echo "                              <td align=\"center\">".$row0['szip1']."</td>\n";
            //echo "                              <td align=\"center\">".date('m/d/y',strtotime($row0['condate']))."</td>\n";
            //echo "                              <td align=\"center\">".date('m/d/y',strtotime($row0['digdate']))."</td>\n";
            echo "                              <form action=\"http://jms/\" method=\"POST\" target=\"JMSmain\">\n";
            echo "                              <input type=\"hidden\" name=\"action\" value=\"reports\">\n";
            echo "                              <input type=\"hidden\" name=\"call\" value=\"chistory\">\n";
            echo "                              <input type=\"hidden\" name=\"cid\" value=\"".$row0['cid']."\">\n";
            echo "                              <input type=\"hidden\" name=\"noffid\" value=\"".$row0['officeid']."\">\n";
            echo "                              <input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
            echo "                              <td align=\"center\">";
            echo "                                  <input class=\"transnb\" type=\"image\" src=\"../images/folder_open.gif\" value=\"View\">\n";
            echo "                              </td>\n";
            echo "                              </form>\n";
            echo "                          </tr>\n";    
        }
        
        echo "                      </table>\n";
        echo "                  </td>\n";
		echo "              </tr>\n";
        echo "          </table>\n";
        echo "      </td>\n";
		echo "  </tr>\n";
        echo "</table>\n";
    }
}

function html_header()
{
   echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">
    <HTML>
        <HEAD>
            <META NAME=\"ROBOTS\" CONTENT=\"NOINDEX, NOFOLLOW\">
            <META http-equiv=\"content-type\" content=\"text/html;charset=utf-8\">
            <TITLE>JMS Lookup</TITLE>
            <LINK href=\"bh_embed.css\" type=\"text/css\" rel=\"stylesheet\">
            <script language=\"Javascript\" type=\"text/javascript\" src=\"../js/extension.js\"></script>
            <script language=\"JavaScript\" type=\"text/javascript\" src=\"../calendar1.js\"></script>
            <script language=\"JavaScript\" type=\"text/javascript\" src=\"../calendar2.js\"></script>
            <script language=\"Javascript\" type=\"text/javascript\" src=\"../js/jquery.js\"></script>
        </HEAD>
        <BODY onLoad=\"window.name = 'JMSLookup'\">
    ";
}

function html_footer()
{
	echo "   </BODY>\n";
	echo "</HTML>\n";
}

if (empty($_REQUEST['act']))
{
    html_header();
    
    echo 'Malformed Request<br/>';
    
    html_footer();
}
else
{
    include ('../connect_db.php');
    
    html_header();
    
    if (!empty($_REQUEST['caller_ani']) && strlen($_REQUEST['caller_ani']) >= 5)
    {
        clookup($_REQUEST['caller_ani']);
    }
    else
    {
        echo 'Empty Request String';
    }
    
    html_footer();
}

?>