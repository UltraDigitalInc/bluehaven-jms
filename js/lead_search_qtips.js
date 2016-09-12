Ext.BLANK_IMAGE_URL = 'images/pixel.gif';

Ext.onReady(function() {
    
    Ext.QuickTips.init();
    
    new Ext.ToolTip({
        target: 'clsearch',
        html: 'Use this search to locate Leads by Last Name, Address, etc.',
        title: 'Customer Search',
        trackMouse:true,
        dismissDelay:0
    });
    
    new Ext.ToolTip({
        target: 'lssearch',
        html: 'Use this search to locate Leads by Source Code',
        title: 'Lead Source Search',
        trackMouse:true,
        dismissDelay:0
    });
    
    new Ext.ToolTip({
        target: 'lrsearch',
        html: 'Use this search to locate Leads by Result Code',
        title: 'Lead Result Search',
        trackMouse:true,
        dismissDelay:0
    });
    
    new Ext.ToolTip({
        target: 'srsearch',
        html: 'Use this search to locate Leads by Sales Rep',
        title: 'Sales Rep Lead Search',
        trackMouse:true,
        dismissDelay:0
    });
    
    new Ext.ToolTip({
        target: 'exsearch',
        html: 'Use this search to locate leads for Exporting to CSV format',
        title: 'Export Lead Search',
        trackMouse:true,
        dismissDelay:0
    });
});