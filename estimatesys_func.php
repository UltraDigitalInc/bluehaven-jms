<?php

function BaseMatrix()
{
    if (isset($_REQUEST['call']) and $_REQUEST['call']=='matrix0')
    {
        EstimateBase($_REQUEST['cid']);
    }
}

function EstimateBase($cid)
{
    //echo __FUNCTION__;
    //echo "  <div id=\"CustomerInfo\"><fieldset class=\"pbouter\"><legend>Customer</legend></fieldset></div>\n";
    ?>
    
    <script type="text/javascript" src="js/jquery_estimatesys_func.js"></script>
    <input type="hidden" id="active_oid" value="<?php echo $_SESSION['officeid']; ?>">
    <input type="hidden" id="active_cid" value="<?php echo $cid; ?>">
    <input type="hidden" id="active_sid" value="<?php echo $_SESSION['securityid']; ?>">
    <div id="LoadStatus"></div>
    <div id="EstimateHead">
        <div id="EstimateMenu">
            <button id="SaveEstimate">Save</button>
            <button id="OpenBidItemDialog">Bid Item</button>
            <button id="OpenEstPBDialog">Pricebook</button>
        </div>
        <div class="clear"></div>
    </div>
    <div id="SalesInfo">
        <div id="CustomerInfo"><fieldset class="estouter"><legend>Customer</legend></fieldset></div>
        <div id="BuildInfo"><fieldset class="estouter"><legend>Build</legend></fieldset></div>
        <div id="OfficeInfo"><fieldset class="estouter"><legend>Office</legend></fieldset></div>
        <div class="clear"></div>
    </div>
    <div id="EstimateMain">
        <div id="EstimateContent">
            <fieldset class="estouter" id="EstimateDetail">
                <legend>Estimate</legend>
                <table id="eItemDetail" class="inner_borders" border=1>
                    <tr class="odd">
                        <td align="center" width="40px"><i>Code</i></td>
                        <td align="center" width="100px"><i>Category</i></td>
                        <td align="center"><i>Description</i></td>
                        <td align="center" width="40px"><i>Quantity</i></td>
                        <td align="center" width="40px"><i>Price</i></td>
                        <td align="center" width="40px"><i>Total</i></td>
                        <td width="40px"><img src="images/pixel.gif"></td>
                    </tr>
                </table>
            </fieldset>
        </div>
        <div id="EstimateInfo">
            <div id="PriceInfo"><fieldset class="estouter"><legend>Estimate Price</legend><div id="EstimatePrice">0.00</div></fieldset></div>
            <div id="CommInfo"><fieldset class="estouter"><legend>Commission</legend><div id="CommissionTotal">0.00</div></fieldset></div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
    
    <?php
}