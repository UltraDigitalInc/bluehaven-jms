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
        
        $qryA = "SELECT did,sdid,pdid,sbody,mbody,dtype FROM jest_doc..doc_main WHERE did = ".$_REQUEST['did'].";";
        $resA = mssql_query($qryA);
        $rowA = mssql_fetch_array($resA);
        $nrowA = mssql_num_rows($resA);
        
        ?>
        
        <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
        <html>
            <head lang="en">
                <link rel="stylesheet" type="text/css" href="../yui/build/fonts/fonts-min.css">
            </head>
            <body class="yui-skin-sam">
        
            <?php echo $rowA['mbody']; ?>
        
            </body>
        </html>
        
        <?php
    }
?>