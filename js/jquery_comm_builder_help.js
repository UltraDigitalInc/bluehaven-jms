$(document).ready(function()
{    
    $('body').append('<div id="hlpdlgdisp" style="display:none"></div>');
    
    var hlpdlg = $("#hlpdlgdisp").dialog({
        autoOpen: false,
        draggable: false,
        resizable: false,
        width: 350,
        height: 150
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
            case 'srhelptext':
                var help_text = '<p><b>Recipient</b></p>Selecting <b>All Sales Reps</b> allows you to create or modify a Commission Profile for all the Sales Reps in this Office.<br><br>Selecting an <b>individual Sales Rep</b> allows you to create or modify Commission Profiles for that specific Sales Rep. Commission profiles for individual Sales Reps take priority and will override a like category profile designated for All Sales Reps.';
            break;
        
            case 'cchelptext':
                var help_text = '<p><b>Category</b></p><p>Select a Commission Category to create a new Commission or modify an existing Commission Category.</p><p><font color="green">GREEN</font> - Category exists and is Active</p><p><font color="red">RED</font> - Category exists and is Inactive</p><p><font color="gray">GREY</font> - Category does not exist</p>';
            break;
        
            case 'ncp_ctype_help':
                var help_text = '<p><b>Type</b> (Required)</p>Select <b>Fixed</b> to set the Commission reward to a Fixed Dollar Amount.<br><br>Select <b>Percent</b> to set the Commission reward to a percentage of the Commission Source.';
            break;
        
            case 'ncp_rwdrate_help':
                var help_text = '<p><b>Percentage Rate</b></p>Percentage by which the Calculation Source will be calculated to reward the Recipient.';
            break;
        
            case 'ncp_rwdamt_help':
                var help_text = '<p><b>Fixed Amount</b></p>Fixed dollar amount to reward the Commission Recipient.';
            break;
        
            case 'ncp_trgsrcval_help':
                var help_text = '<p><b>Source</b></p><br><p><i>Fixed</i><br>Commission is the value of the selected Source.</p><p><i>Percentage</i><br>Percentage Rate is calculated against the value of this selection.</p>';
            break;
        
            case 'ncp_trgsrc_help':
                var help_text = '<p><b>Trigger</b></p><br><p>Determines what value activates this commission.</p><p>NOTE: If Trigger is set to <b>None</b>, Commission will always appear.</p>';
            break;
        
            case 'ncp_trgwght_help':
                var help_text = '<p><b>Weight</b></p>Determines the threshold that causes this commission to activate.';
            break;
        }
        
        return help_text;
    }
});