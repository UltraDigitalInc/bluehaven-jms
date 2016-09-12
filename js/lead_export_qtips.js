Ext.BLANK_IMAGE_URL = 'images/pixel.gif';

Ext.onReady(function() {
    
    Ext.QuickTips.init();
    
    new Ext.ToolTip({
        target: 'exsearch',
        html: 'Use this search to locate leads for Exporting to CSV format',
        title: 'Lead Export',
        trackMouse:true,
        dismissDelay:0
    });
});