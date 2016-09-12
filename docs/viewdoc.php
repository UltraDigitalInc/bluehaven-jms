<?php

    session_start();
    
    error_reporting(E_ALL);
    ini_set('display_errors','On');
    
    /*echo '<pre>';
    print_r($_REQUEST);
    echo '</pre>';*/

    if (isset($_SESSION['securityid']))
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
                <title>Job Management System Manual v10.04.09</title>
                
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
                <div id="doc3" class="yui-t1">
                    <div id="hd" role="banner">
                        <?php

                        docmatrix_header();

                        ?>
                    </div>
                    <div id="bd" role="main">   
                        <div id="yui-main">   
                            <div class="yui-b">
                                <div role="application" class="yui-g">
                                
                                <?php
                                
                                if (isset($_REQUEST['call']) && $_REQUEST['call']=='add')
                                {
                                    createdoc();
                                }
                                elseif (isset($_REQUEST['call']) && $_REQUEST['call']=='create2')
                                {
                                    newdoc();
                                }
                                elseif (isset($_REQUEST['call']) && $_REQUEST['call']=='delete')
                                {
                                    deletedoc();
                                }
                                else
                                {
                                    docmatrix_content();
                                }
                                
                                ?>
                                
                                </div>
                            </div>  
                        </div>
                        <div role="navigation" class="yui-b">
                            
                        <?php

                        docmatrix_index();

                        ?>
                        
                        </div>
                    </div>
                    <div id="ft" role="complementary">
                        <?php
                        
                        docmatrix_footer();
                        
                        ?>
                    </div>  
                </div>
                
                
            </body>
        </html>
        
        <?php
    }
    else
    {
        echo 'You do not have appropriate rights to view this resource.';
    }
?>