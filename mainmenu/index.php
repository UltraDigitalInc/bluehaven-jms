<?php
    session_start();
    
    $_SESSION['uid']=26;

    if (!is_array($_SESSION))
    {
        die('Session Error!');
    }

?>

<html>
    <head>
        
        <!-- Fonts CSS - Recommended but not required -->
        <link rel="stylesheet" type="text/css" href="../yui/build/fonts/fonts-min.css">
        
        <!-- Core + Skin CSS -->
        <link rel="stylesheet" type="text/css" href="../yui/build/menu/assets/skins/sam/menu.css">
        
        <!-- Dependencies --> 
        <script type="text/javascript" src="../yui/build/yahoo-dom-event/yahoo-dom-event.js"></script>
        <script type="text/javascript" src="../yui/build/container/container_core-min.js"></script>
        
        <!-- Source File -->
        <script type="text/javascript" src="../yui/build/menu/menu-min.js"></script>
        <script type="text/javascript" src="menu.js"></script>
        
        <link rel="stylesheet" type="text/css" href="./css/core.css">

    </head>
    <body class="yui-skin-sam">
        <table align="center" width="750">
            <tr>
                <td>
                    <table align="right">
                        <tr>
                            <td><b>Test Company</b></td>
                            <td> | </td>
                            <td><b>Joe User</b></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    
                    <div id="menubar" class="yuimenubar yuimenubarnav">
                        <div class="bd">
                            <ul class="first-of-type">
                                
                                <li class="yuimenubaritem first-of-type"><a class="yuimenubaritemlabel" href="#leads"> Customers</a>
                                    
                                    <div id="leads" class="yuimenu">
                                        <div class="bd">
                                            
                                            <ul class="first-of-type">
                                                <li class="yuimenuitem">
                                                    <a class="yuimenuitemlabel" href="#"> Quick Search</a>
                                                </li>
                                                <li class="yuimenuitem">
                                                    <a class="yuimenuitemlabel" href="#"> Appointments</a>
                                                </li>
                                                <li class="yuimenuitem">
                                                    <a class="yuimenuitemlabel" href="#"> Callbacks</a>
                                                </li>
                                                
                                            </ul>
                                            
                                            <ul>
                                                <li class="yuimenuitem">
                                                    <a class="yuimenuitemlabel" href="#"> Open</a>
                    
                                                    <div id="open" class="yuimenu">
                                                        <div class="bd">
                                                            <ul class="first-of-type">
                                                                <li class="yuimenuitem">
                                                                    <a class="yuimenuitemlabel" href="#leads"> Leads</a>
                                                                </li>
                                                                <li class="yuimenuitem">
                                                                    <a class="yuimenuitemlabel" href="#estimates"> Estimates</a>
                                                                </li>
                                                                <li class="yuimenuitem">
                                                                    <a class="yuimenuitemlabel" href="#contract"> Contracts</a>
                                                                </li>
                                                                <li class="yuimenuitem">
                                                                    <a class="yuimenuitemlabel" href="#jobs"> Jobs</a>
                                                                </li>
                                                            </ul>            
                                                        </div>
                                                    </div>                    
                                                
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    
                                </li>
                                
                                <li class="yuimenubaritem"><a class="yuimenubaritemlabel" href="#reports"> Reports</a>
                                    
                                    <div id="reports" class="yuimenu">
                                        <div class="bd">
                                            
                                            <ul class="first-of-type">
                                                <li class="yuimenuitem">
                                                    <a class="yuimenuitemlabel" href="#"> Dig Standings</a>
                                                </li>
                                                <li class="yuimenuitem">
                                                    <a class="yuimenuitemlabel" href="#"> Sales & Commission</a>
                                                </li>
                                            </ul>            
                    
                                            <ul>
                                                <li class="yuimenuitem">
                                                    <a class="yuimenuitemlabel" href="#"> Lead-Cont-Dig</a>
                                                </li>
                                                <li class="yuimenuitem">
                                                    <a class="yuimenuitemlabel" href="#"> Lead Source</a>
                                                </li>
                                            </ul>
                                            
                                            <ul>
                                                <li class="yuimenuitem">
                                                    <a class="yuimenuitemlabel" href="#"> Operating</a>
                    
                                                    <div id="operating" class="yuimenu">
                                                        <div class="bd">
                                                            <ul class="first-of-type">
                                                                <li class="yuimenuitem">
                                                                    <a class="yuimenuitemlabel" href="#admin"> Admin</a>
                                                                </li>
                                                                <li class="yuimenuitem">
                                                                    <a class="yuimenuitemlabel" href="#cib"> Cash in Bank</a>
                                                                </li>
                                                                <li class="yuimenuitem">
                                                                    <a class="yuimenuitemlabel" href="#110p"> 110%</a>
                                                                </li>
                                                                <li class="yuimenuitem">
                                                                    <a class="yuimenuitemlabel" href="#arec"> AR</a>
                                                                </li>
                                                            </ul>            
                                                        </div>
                                                    </div>                    
                                                
                                                </li>
                                            </ul>
                                            
                                        </div>
                                    </div>
                                    
                                </li>
                                
                                <li class="yuimenubaritem"><a class="yuimenubaritemlabel" href="#messages">Messages</a>
                                    
                                    <div id="messages" class="yuimenu">
                                        <div class="bd">
                                            
                                            <ul class="first-of-type">
                                                <li class="yuimenuitem">
                                                    <a class="yuimenuitemlabel" href="#"> Search</a>
                                                </li>
                                            </ul>
                                            
                                            <ul>
                                                <li class="yuimenuitem">
                                                    <a class="yuimenuitemlabel" href="#"> Compose</a>
                                                </li>
                                                <li class="yuimenuitem">
                                                    <a class="yuimenuitemlabel" href="#"> View All</a>
                                                </li>
                                            </ul>
                                            
                                        </div>
                                    </div>                      
                    
                                </li>
                                
                                <li class="yuimenubaritem">
                                    
                                    <a class="yuimenubaritemlabel" href="#system">System</a>
                    
                                    <div id="system" class="yuimenu">
                                        <div class="bd">
                                            
                                            <?php
                                            
                                            if (isset($_SESSION['uid']) && $_SESSION['uid']==26)
                                            {
                                            
                                            ?>
                                            <ul class="first-of-type">
                                                <li class="yuimenuitem">
                                                    <a class="yuimenuitemlabel" href="#company"> Change Company</a>
                    
                                                    <div id="company" class="yuimenu">
                                                        <div class="bd">
                                                            <ul class="first-of-type">
                                                                <li class="yuimenuitem">
                                                                    <a class="yuimenuitemlabel" href="#010"> Company 1</a>
                                                                </li>
                                                                <li class="yuimenuitem">
                                                                    <a class="yuimenuitemlabel" href="#020"> Company 2</a>
                                                                </li>
                                                                <li class="yuimenuitem">
                                                                    <a class="yuimenuitemlabel" href="#030"> Company 3</a>
                                                                </li>
                                                                <li class="yuimenuitem">
                                                                    <a class="yuimenuitemlabel" href="#040"> Company 4</a>
                                                                </li>
                                                            </ul>            
                                                        </div>
                                                    </div>                    
                                                
                                                </li>
                                            </ul>
                                            
                                            <ul>
                                            
                                            <?php
                                            }
                                            else
                                            {
                                            ?>
                                            

                                            <ul class="first-of-type">
                                            
                                            <?php
                                            }
                                            ?>
                                        
                                                <li class="yuimenuitem">
                                                    <a class="yuimenuitemlabel" href="#"> Change Password</a>
                                                </li>
                                                <li class="yuimenuitem">
                                                    <a class="yuimenuitemlabel" href="#"> PriceBook Adjust</a>
                                                </li>
                                            </ul>
                                            <ul>
                                                <li class="yuimenuitem">
                                                    <a class="yuimenuitemlabel" href="#"> Logout</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>                      
                    
                                </li>
                            </ul>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </body>
</html>