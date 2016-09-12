$(document).ready(function()
{    
    $('body').append('<div id="hlpdlgdisp" style="display:none"></div>');
    
    var hlpdlg = $("#hlpdlgdisp").dialog({
        autoOpen: false,
        draggable: false,
        resizable: false,
        width: 250,
        height: 100
    });
    
    $(".hlpdlg").live('mouseover',function(event) {
        //alert($(this).attr('id'));
        hlpdlg.dialog("open");
        //alert('Test');
    });
    
    $(".hlpdlg").live('mousemove',function(event) {        
        $('#hlpdlgdisp').html(displayHelp(event));
        
        hlpdlg.dialog("option", "position", {
            my: "left top",
            at: "right bottom",
            of: event,
            offset: "15 15"
        });
        
        $(".ui-dialog-titlebar").hide();
    });
    
    $(".hlpdlg").live('mouseout',function(event) {
        event.preventDefault();
        hlpdlg.dialog("close");
    });
    
    function displayHelp(event,pel)
    {
        var help_text = '';
        
        switch ($(event.target).attr('id'))
        {
            case 'objExportCst':
                help_text += 'Provides Customer Information in CSV format.</p><br>';
            break;
            
            case 'objExportJob':
                help_text += 'Provides a detailed list of Cost of Construction including: Phase, Item, Quantity, and Price in CSV format.</p><br>';
            break;
        }
        
        return help_text;
    }
});