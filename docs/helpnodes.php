<?php

    session_start();
    
    error_reporting(E_ALL);
    ini_set('display_errors','On');
    
    /*echo '<pre>';
    print_r($_REQUEST);
    echo '</pre>';*/

    if (isset($_SESSION['securityid']) && isset($_REQUEST['nodeid']))
    {
        include ('..\connect_db.php');
        include ('..\common_func.php');
        include ('..\doc_func.php');
        
        header("Cache-control: private");
        
        ?>
        
        <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
        <html>
            <head lang="en">
                <meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
                <meta http-equiv="content-type" content="text/html;charset=ISO-8859-1">
                <title>Job Management System Help Node Creation</title>
                
                <!-- Skin CSS files -->
                <link rel="stylesheet" type="text/css" href="../yui/build/reset-fonts-grids/reset-fonts-grids.css">
                <link rel="stylesheet" type="text/css" href="../yui/build/editor/assets/skins/sam/simpleeditor.css" />
                <link rel="stylesheet" type="text/css" href="../css/jms_docs.css" />
                
                <script type="text/javascript" src="../yui/build/yahoo-dom-event/yahoo-dom-event.js"></script>
                <script type="text/javascript" src="../yui/build/element/element-min.js"></script>
                <script type="text/javascript" src="../yui/build/container/container_core-min.js"></script>
                <script type="text/javascript" src="../yui/build/editor/simpleeditor-min.js"></script>

                <script language="javascript" type="text/javascript" src="../js/extension.js"></script>
            </head>
            <body class="yui-skin-sam">

            <?php
            
            if (isset($_REQUEST['call']) && $_REQUEST['call']=='edit')
            {
                editnode($_REQUEST['nodeid']);
            }
            elseif (isset($_REQUEST['call']) && $_REQUEST['call']=='update')
            {
                updatenode();
            }
            elseif (isset($_REQUEST['call']) && $_REQUEST['call']=='save')
            {
                savenode();
            }
            elseif (isset($_REQUEST['call']) && $_REQUEST['call']=='create')
            {
                createnode($_REQUEST['nodeid']);
            }
            
            ?>

            </body>
        </html>
        
        <?php
    }
    else
    {
        echo 'You do not have appropriate rights to view this resource.';
    }
?>