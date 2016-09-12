Ext.BLANK_IMAGE_URL = 'images/pixel.gif';

Ext.onReady(function() {
    
    Ext.QuickTips.init();
    
    new Ext.ToolTip({
        target: 'bpadj1',
        html: 'Use this tool to create a Base Pool Price <b>Adjustment</b> applied to all future Quotes',
        title: 'Base Price Adjust',
        trackMouse:true,
        dismissDelay:0
    });
    
    new Ext.ToolTip({
        target: 'pbadj1',
        html: 'Use this tool to create price <b>Adjustments</b> on most Pricebook items used in a Quote<br>Step 1: Enter Adjustment amount in the Adjustment field<br>Step 2: Click Yes to make the adjustment active for all Future Quotes (or Click No to disable adjustment for all future quotes)<br>Step 3: Click the <b>Save</b> icon',
        title: 'Pricebook Adjustments',
        trackMouse:true,
        dismissDelay:0
    });
    
    new Ext.ToolTip({
        target: 'ppadj1',
        html: 'Use this tool to create PriceBook Items for use in a Quote<br>Step 1: Enter <b>Description</b> (keep it short).<br>Step 2: Set <b>Auto Add</b> On to make the Item add to all future quotes automatically<br>Step 3: Set <b>Active</b> On to make this item avaible for selection in the Pricebook for all future Quotes<br> Step 4: Click the <b>Save</b> Icon',
        title: 'Personal Pricebook Items',
        trackMouse:true,
        dismissDelay:0        
    });
});