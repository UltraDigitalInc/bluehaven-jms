Ext.BLANK_IMAGE_URL = 'images/pixel.gif';

Ext.onReady(function() {
    
    Ext.QuickTips.init();
    
    new Ext.ToolTip({
        target: 'bhleads',
        html: 'These leads are provided by BHNM from a variety of sources',
        title: 'BHNM - Provided Leads',
        trackMouse:true,
        dismissDelay:0
    });
    
    new Ext.ToolTip({
        target: 'manleads',
        html: 'These leads are manually added to the System by an Office',
        title: 'Manually Entered Leads',
        trackMouse:true,
        dismissDelay:0
    });
});