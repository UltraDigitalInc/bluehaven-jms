<?php

function convert_smart_quotes($string)
{
    $search = array(
                        chr(145),
                        chr(146),
                        chr(147),
                        chr(148),
                        chr(151)
                    );
    $replace = array(
                        "'",
                        "'",
                        '"',
                        '"',
                        '-'
                    );
    return str_replace($search, $replace, $string);
}

function showdoc($did,$sbody,$mbody)
{
    //echo "<div>".$mbody."</div>";
    
    echo "<iframe src=\"viewpage.php?did=".$did."\" frameborder=\"0\" width=\"770\" height=\"500\"></iframe>";
}

function createnode($nodeid)
{
    ?>
    
    <form name="createnode" action="helpnodes.php" method="post">
        <input type="hidden" name="action" value="docs">
        <input type="hidden" name="call" value="save">
        <input type="hidden" name="nodeid" value="<?php echo $nodeid; ?>">
        <table>
            <tr>
                <td align="left">
                    <b>Help Node Title</b>
                </td>
            </tr>
            <tr>
                <td align="left">
                    <input type="text" name="nodetitle" size="50" maxlength="64">
                </td>
            </tr>
            <tr>
                <td align="left">
                    <b>Hover Text</b>
                </td>
            </tr>
            <tr>
                <td align="left">
                    <input type="text" name="imgtext" size="50" maxlength="64">
                </td>
            </tr>
            <tr>
                <td align="left">
                    <b>Help Node Body Text</b>
                </td>
            </tr>
            <tr>
                <td>
                    <textarea id="nodetext" name="nodetext" rows="20" cols="75"></textarea>
                </td>
            </tr>
            <tr>
                <td align="left">
                    <b>Help Node Footnote</b>
                </td>
            </tr>
            <tr>
                <td align="left">
                    <input type="text" name="nodefoot" size="50" maxlength="96" value="Some content may not be visible due to Security Restrictions">
                </td>
            </tr>
            <tr>
                <td align="right">
                    <input class="transnb" type="image" src="../images/save.gif" title="Save">
                </td>
            </tr>
        </table>
    </form>
    
    <script> 

    (function() {
        var Dom = YAHOO.util.Dom,
            Event = YAHOO.util.Event;
        
        var myConfig = {
            height: '400px',
            width: '750px',
            dompath: true,
            focusAtStart: true,
            handleSubmit: true
        };
     
        //YAHOO.log('Create the Editor..', 'info', 'example');
        var myEditor = new YAHOO.widget.SimpleEditor('nodetext', myConfig);
        myEditor.render();
     
    })();
    </script>
    
    <?php
}

function editnode($nodeid)
{
    $qryA = "SELECT * FROM jest_doc..HelpNode WHERE nodeid = '".$nodeid."';";
    $resA = mssql_query($qryA);
    $rowA = mssql_fetch_array($resA);
    
    ?>
    
    <form name="editnode" action="helpnodes.php" method="post">
        <input type="hidden" name="action" value="docs">
        <input type="hidden" name="call" value="update">
        <input type="hidden" name="nodeid" value="<?php echo $rowA['nodeid']; ?>">
        <table>
            <tr>
                <td align="left">
                    <b>Help Node Title</b>
                </td>
            </tr>
            <tr>
                <td align="left">
                    <input type="text" name="nodetitle" value="<?php echo $rowA['nodetitle']; ?>" size="50" maxlength="64">
                </td>
            </tr>
            <tr>
                <td align="left">
                    <b>Hover Text</b>
                </td>
            </tr>
            <tr>
                <td align="left">
                    <input type="text" name="imgtext" value="<?php echo $rowA['imgtext']; ?>" size="50" maxlength="64">
                </td>
            </tr>
            <tr>
                <td align="left">
                    <b>Help Node Body Text</b>
                </td>
            </tr>
            <tr>
                <td>
                    <textarea id="nodetext" name="nodetext" rows="20" cols="75"><?php echo $rowA['nodetext']; ?></textarea>
                </td>
            </tr>
            <tr>
                <td align="left">
                    <b>Help Node Footnote</b>
                </td>
            </tr>
            <tr>
                <td align="left">
                    <input type="text" name="nodefoot" value="<?php echo $rowA['nodefoot']; ?>" size="50" maxlength="96">
                </td>
            </tr>
            <tr>
                <td align="right">
                    <input class="transnb" type="image" src="../images/save.gif" title="Save">
                </td>
            </tr>
        </table>
    </form>
    
    <script> 

    (function() {
        var Dom = YAHOO.util.Dom,
            Event = YAHOO.util.Event;
        
        var myConfig = {
            height: '400px',
            width: '750px',
            dompath: true,
            focusAtStart: true,
            handleSubmit: true
        };
     
        //YAHOO.log('Create the Editor..', 'info', 'example');
        var myEditor = new YAHOO.widget.SimpleEditor('nodetext', myConfig);
        myEditor.render();
     
    })();
    </script>
    
    <?php
}

function savenode()
{
    error_reporting(E_ALL);
    ini_set('display_errors','On');
    
    //print_r($_REQUEST);
    
    $qry  = "INSERT INTO jest_doc..HelpNode (nodeid,nodetitle,nodetext,nodefoot,imgtext) VALUES ('".$_REQUEST['nodeid']."','".$_REQUEST['nodetitle']."','".$_REQUEST['nodetext']."','".$_REQUEST['nodefoot']."','".$_REQUEST['imgtext']."');";
    $res  = mssql_query($qry);

    editnode($_REQUEST['nodeid']);
}

function updatenode()
{
    error_reporting(E_ALL);
    ini_set('display_errors','On');
    
    //print_r($_REQUEST);
    
    $qry  = "UPDATE jest_doc..HelpNode SET nodetitle='".$_REQUEST['nodetitle']."',nodetext='".$_REQUEST['nodetext']."',nodefoot='".$_REQUEST['nodefoot']."',imgtext='".$_REQUEST['imgtext']."' WHERE nodeid = '".$_REQUEST['nodeid']."';";
    $res  = mssql_query($qry);

    editnode($_REQUEST['nodeid']);
}

function createdoc()
{
    $qryA = "SELECT did,sdid,pdid,sbody,mbody,dtype FROM jest_doc..doc_main WHERE did = ".$_REQUEST['did'].";";
    $resA = mssql_query($qryA);
    $rowA = mssql_fetch_array($resA);
    $nrowA = mssql_num_rows($resA);
    
    ?>
    
    <form name="editdoc" action="viewdoc.php" method="post">
        <input type="hidden" name="action" value="docs">
        <input type="hidden" name="call" value="create2">
        <input type="hidden" name="sdid" value="<?php echo $rowA['sdid']; ?>">
        <input type="hidden" name="pdid" value="<?php echo $rowA['did']; ?>">
        <input type="hidden" name="dtype" value="<?php echo $_REQUEST['dtype']; ?>">
        <b>Title</b>
        <input type="text" name="sbody" size="50" title="Document Title">
        <input class="transnb" type="image" src="../images/save.gif" title="Save">
    </form>
    
    <?php
}

function editdoc($did,$sbody,$mbody)
{
    ?>
    
    <form name="editdoc" action="viewdoc.php" method="post">
        <input type="hidden" name="action" value="docs">
        <input type="hidden" name="call" value="save">
        <input type="hidden" name="did" value="<?php echo $did; ?>">
        <textarea id="mbody" name="mbody" rows="20" cols="75"><?php echo $mbody; ?></textarea>
        <input class="transnb" type="image" src="../images/save.gif" title="Save">
    </form>
    
                <script> 
 
                (function() {
                    var Dom = YAHOO.util.Dom,
                        Event = YAHOO.util.Event;
                    
                    var myConfig = {
                        height: '400px',
                        width: '750px',
                        dompath: true,
                        focusAtStart: true,
                        handleSubmit: true
                    };
                 
                    YAHOO.log('Create the Editor..', 'info', 'example');
                    var myEditor = new YAHOO.widget.SimpleEditor('mbody', myConfig);
                    myEditor.render();
                 
                })();
                </script>
    
    <?php
}

function newdoc()
{
    error_reporting(E_ALL);
    ini_set('display_errors','On');
    
    $qryA = "SELECT MAX(spge) as mspge FROM jest_doc..doc_main WHERE pdid = ".$_REQUEST['pdid'].";";
    $resA  = mssql_query($qryA);
    $rowA = mssql_fetch_array($resA);
    
    $qryB  = "INSERT INTO jest_doc..doc_main (sdid,pdid,dtype,spge,sbody,slevel,active,insid) VALUES (".$_REQUEST['sdid'].",".$_REQUEST['pdid'].",'".$_REQUEST['dtype']."',".($rowA['mspge']+1).",'".$_REQUEST['sbody']."',1,1,".$_SESSION['securityid'].");";
    $resB  = mssql_query($qryB);
    
    //echo $qryB.'<br>';
}

function savedoc()
{
    error_reporting(E_ALL);
    ini_set('display_errors','On');
    
    //print_r($_REQUEST);
    
    $qry  = "UPDATE jest_doc..doc_main SET mbody='".$_REQUEST['mbody']."',udate=getdate(),editid=".$_SESSION['securityid']." WHERE did = ".$_REQUEST['did'].";";
    $res  = mssql_query($qry);
    
    $qryA = "SELECT did,sbody,mbody FROM jest_doc..doc_main WHERE did = ".$_REQUEST['did'].";";
    $resA = mssql_query($qryA);
    $rowA = mssql_fetch_array($resA);

    showdoc($rowA['did'],$rowA['sbody'],$rowA['mbody']);
}

function deletedoc()
{
    $qry  = "UPDATE jest_doc..doc_main SET active=0 WHERE did=".$_REQUEST['did'].";";
    $res  = mssql_query($qry);
    //$nrow = mssql_num_rows($res);
}

function listdocs()
{
    error_reporting(E_ALL);
    ini_set('display_errors','On');
    
    $qry  = "select * from jest_doc..doc_main where active >= 1 and pdid=0 order by dtype asc,sbody asc;";
    $res  = mssql_query($qry);
    $nrow = mssql_num_rows($res);
    
    if ($nrow > 0)
    {
        while ($row  = mssql_fetch_array($res))
        {
            echo "<a href=\".\docs\viewdoc.php?action=docs&call=view&did=".$row['did']."&pdid=".$row['did']."&position=idx\" target=\"subDocViewer\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','subDocViewer','HEIGHT=625,WIDTH=975,status=0,scrollbars=1,dependent=1,resizable=0,toolbar=0,menubar=0,location=0,directories=0'); window.status=''; return true;\">".$row['sbody']."</a>";
        }
    }
    else
    {
        echo 'No Docs Yet!';
    }
}

function docmatrix_header()
{
    $dtitle='Job Management System Manual';
    
    ?>
    
    <table width="100%" border="1">
        <tr><td>
            <table width="100%">
                <tr>
                    <td align="left">
                        <h2><?php echo $dtitle; ?></h2>
                        <div class="htitleedit">
                            
                        <?php
                        
                        //echo "          <a href=\"viewdoc.php?action=docs&call=edit&did=1&pdid=0\" target=\"_top\"><img src=\"../images/book_edit.png\" height=\"10\" width=\"10\" title=\"Edit this Document\"></a>";
                        
                        ?>
                        
                        </div>
                    </td>
                    <td align="right">
                        <table>
                            <tr>
                                <td align="center" width="30px">
                                    <form id="docprint" action="viewdoc.php" target="_top">
                                        <input type="hidden" name="action" value="docs">
                                        <input type="hidden" name="call" value="view">
                                        <input type="hidden" name="did" value="1">
                                        <input class="transnb" type="image" src="../images/house.png" title="Home">
                                    </form>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td></tr>
    </table>
    
    <?php
}

function docmatrix_footer()
{
    ?>
    
    <center><font style="font-size:77%;">Copyright &copy; Blue Haven Pools & Spas</font></center>
    
    <?php
}

function docmatrix_index()
{
    $qry0	= "SELECT did,sdid,pdid,sbody FROM jest_doc..doc_main WHERE did=1 and active>=1 ORDER BY spge;";
	$res0	= mssql_query($qry0);
    $row0	= mssql_fetch_array($res0);
    
    //$qry1	= "SELECT did,sdid,pdid,sbody FROM jest_doc..doc_main WHERE sdid=".$row0['did']." AND dtype='Chapter' ORDER BY spge;";
    $qry1	= "SELECT did,sdid,pdid,sbody FROM jest_doc..doc_main WHERE pdid=".$row0['did']." and active>=1 ORDER BY spge;";
	$res1	= mssql_query($qry1);
	$nrow1	= mssql_num_rows($res1);
    
    ?>
    
    <table width="100%">
        <tr><td>

            <table width="100%" height="550px">
                <tr>
                    <td align="left" valign="top">
                        
                        <ul id="menu" class="first-of-type">
                            
                            <?php
                            
                            while ($row1	= mssql_fetch_array($res1))
                            {
                                $qry2	= "SELECT did,sdid,pdid,sbody FROM jest_doc..doc_main WHERE pdid=".$row1['did']." and active>=1 ORDER BY spge;";
                                $res2	= mssql_query($qry2);
                                $nrow2	= mssql_num_rows($res2);
                                
                                echo "<li>".$row1['sbody'];
                                
                                if ($_SESSION['securityid']==26 || $_SESSION['securityid']==332 || $_SESSION['securityid']==1732)
                                {
                                    //echo "          <div id=\"hiddendiv\">";
                                    echo "          <a href=\"viewdoc.php?action=docs&call=add&dtype=Page&did=".$row1['did']."\" target=\"_top\"><img src=\"../images/add.png\" height=\"10\" width=\"10\" title=\"Add New Page\"></a>";
                                    //echo "          </div>";
                                }
                                
                                if ($nrow2 > 0)
                                {
                                    echo "  <ul class=\"first-of-type\">";
                                
                                    while($row2	= mssql_fetch_array($res2))
                                    {
                                        $qry3	= "SELECT did,sdid,pdid,sbody FROM jest_doc..doc_main WHERE pdid=".$row2['did']." and active>=1 ORDER BY spge;";
                                        $res3	= mssql_query($qry3);
                                        $nrow3	= mssql_num_rows($res3);
                                        
                                        //echo $qry3.'<br>';
                                        echo "      <li>";
                                        echo "          <a href=\"viewdoc.php?action=docs&call=view&did=".$row2['did']."&pdid=".$row2['pdid']."\" target=\"_top\">".$row2['sbody']."</a>";
                                        
                                        if ($_SESSION['securityid']==26 || $_SESSION['securityid']==332 || $_SESSION['securityid']==1732)
                                        {
                                            //echo "          <div id=\"hiddendiv\">";
                                            echo "          <a href=\"viewdoc.php?action=docs&call=edit&did=".$row2['did']."&pdid=".$row2['pdid']."\" target=\"_top\"><img src=\"../images/book_edit.png\" height=\"10\" width=\"10\" title=\"Edit this Document\"></a>";
                                            echo "          <a href=\"viewdoc.php?action=docs&call=add&dtype=Topic&did=".$row2['did']."\" target=\"_top\"><img src=\"../images/add.png\" height=\"10\" width=\"10\" title=\"Add New Topic\"></a>";
                                            
                                            if ($nrow3 == 0)
                                            {
                                                echo "          <a onClick=\"return ConfirmDestroy();\" href=\"viewdoc.php?action=docs&call=delete&did=".$row2['did']."\" target=\"_top\"><img src=\"../images/cross.png\" height=\"10\" width=\"10\" title=\"Delete this Document\"></a>";
                                            }
                                        }
                                        //echo "      </div>";
                                        
                                        if ($nrow3 > 0)
                                        {
                                            echo "  <ul class=\"first-of-type\">";
                                        
                                            while($row3	= mssql_fetch_array($res3))
                                            {
                                                
                                                echo "      <li>";
                                                echo "          <a href=\"viewdoc.php?action=docs&call=view&did=".$row3['did']."&pdid=".$row3['pdid']."\" target=\"_top\">".$row3['sbody']."</a>";
                                                
                                                if ($_SESSION['securityid']==26 || $_SESSION['securityid']==332 || $_SESSION['securityid']==1732)
                                                {
                                                    echo "          <a href=\"viewdoc.php?action=docs&call=edit&did=".$row3['did']."&pdid=".$row3['pdid']."\" target=\"_top\"><img src=\"../images/book_edit.png\" height=\"10\" width=\"10\" title=\"Edit this Document\"></a>";
                                                    echo "          <a onClick=\"return ConfirmDestroy();\" href=\"viewdoc.php?action=docs&call=delete&did=".$row3['did']."\" target=\"_top\"><img src=\"../images/cross.png\" height=\"10\" width=\"10\" title=\"Delete this Document\"></a>";
                                                }
                                                
                                                echo "      </li>";
                                            }
                                            
                                            echo "  </ul>";
                                        }
                                        
                                        echo "      </li>";
                                    }
                                    
                                    echo "  </ul>";
                                }
                                
                                echo "</li>";
                            }
                            
                            ?>
    
                        </ul>
            
                    </td>
                </tr>
            </table>

        </td></tr>
    </table>

    <?php
}

function docmatrix_content()
{
    
    $qry	= "SELECT * FROM jest_doc..doc_main WHERE did=".$_REQUEST['did']." AND sdid!=0;";
	$res	= mssql_query($qry);
	$row	= mssql_fetch_array($res);
    $nrow	= mssql_num_rows($res);
    
    if ($nrow > 0)
    {
        $pdtitle='';
        
        $qry0a	= "SELECT * FROM jest_doc..doc_main WHERE did=".$row['pdid'].";";
        $res0a	= mssql_query($qry0a);
        $row0a	= mssql_fetch_array($res0a);
        
        if (isset($row0a['pdid']) && $row['pdid']!=0)
        {        
            $pdtitle = $row0a['sbody'].' > ';
        }
        
        $dtitle = $pdtitle.$row['sbody'];
    }
    else
    {
        $dtitle='';
    }
    
    ?>
    <table width="100%">
        <tr><td>
            <table width="100%" height="550px">
                <tr>
                    <td align="left" valign="top">
                        <table width="100%">
                            <tr>
                                <td align="left" valign="top">
                                    <b><?php echo $dtitle; ?></b>
                                </td>
                                <td align="right" valign="top">
                                    <?php
                                    
                                    if ($_SESSION['tlev'] >= 999999999999999999)
                                    {
                                        ?>
                                        
                                        <form id="docedit" action="viewdoc.php" target="_top">
                                            <input type="hidden" name="action" value="docs">
                                            <input type="hidden" name="call" value="edit">
                                            <input type="hidden" name="did" value="<?php echo $row['did']; ?>">
                                            <input class="transnb" type="image" src="../images/book_edit.png" title="Edit Page">
                                        </form>
                                        
                                        <?php
                                    }
                                    
                                    ?>
                                </td>
                            </tr>    
                            <tr>
                                <td align="left" valign="top" colspan="2">
                                    
                                <?php

                                    if ($nrow > 0)
                                    {
                                        // Manual Content
                                        if (isset($_REQUEST['call']) && $_REQUEST['call']=='edit')
                                        {
                                            editdoc($row['did'],$row['sbody'],$row['mbody']);
                                        }
                                        elseif (isset($_REQUEST['call']) && $_REQUEST['call']=='save')
                                        {
                                            savedoc($row['did'],$row['sbody'],$row['mbody']);
                                        }
                                        else
                                        {
                                            showdoc($row['did'],$row['sbody'],$row['mbody']);
                                        }
                                    }
                                    //else
                                    //{
                                    //    echo 'Click on ab Topic from the Index<br><p>';
                                    //}

                                ?>

                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td></tr>
    </table>
    
    <?php
}

function docmatrix()
{
    //echo 'Matrix...<br>';
    if (!isset($_REQUEST['call']) || $_REQUEST['call']=='list')
    {
        listdocs();
    } 
    elseif ($_REQUEST['call']=='view')
    {
        showdoc();
    }
    elseif ($_REQUEST['call']=='create')
    {
        createdoc();
    }
    elseif ($_REQUEST['call']=='save')
    {
        //echo 'Saving...';
        savedoc();
    }
    //elseif ($_REQUEST['call']=='edit')
    //{
    //    editdoc();
    //}
    elseif ($_REQUEST['call']=='delete')
    {
        deletedoc();
    }
    elseif ($_REQUEST['call']=='search')
    {
        searchdoc();
    }
    elseif ($_REQUEST['call']=='addnote')
    {
        addnote();
    }
    elseif ($_REQUEST['call']=='createnote')
    {
        createnote();
    }
    elseif ($_REQUEST['call']=='editnote')
    {
        editnote();
    }
    elseif ($_REQUEST['call']=='savenote')
    {
        savenote();
    }
}

?>