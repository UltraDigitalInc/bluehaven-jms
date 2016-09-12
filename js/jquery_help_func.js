$(document).ready(function()
{
    function displayHelp(event)
    {
        var help_text = '';
        $('#NewCommOptionInfo').append('<div id="js_helpnode"></div>');
        
        switch ($(event.target).attr('id'))
        {
            case 'ncp_salesrep':
                var help_text = 'Selecting All assigns the Commission Profile to All Sales Reps. Selecting a specific Sales rep assigns the Commission Profile to that Sales Rep only.';
            break;
        
            case 'ncp_ctgry':
                var help_text = 'Determines Commission Type';
            break;
        }
        
        if (event.type=='mouseover')
        {
            $('#js_helpnode').append(help_text);
        }
        
        if (event.type=='mouseout')
        {
            $('#js_helpnode').text('');
        }
    }
});