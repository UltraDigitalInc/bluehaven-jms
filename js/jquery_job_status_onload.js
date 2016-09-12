$(document).ready(function()
{
    var ajxaccscript    = 'subs/ajax_accounting_req.php';
    
    $.post(ajxaccscript,
    {
        call : 'accountingsystem',
        subq : 'get_Job_Status',
        usr_oid : $('#usr_oid').val(),
        usr_jobid : $('#usr_jobid').val(),
        usr_jadd : $('#usr_jadd').val(),
        optype : 'table'
    },
    function(data)
    {
        //alert (data);
        
        switch(parseInt(data))
        {
            case 0:
                $('.JobStatusHeader').css('background-color','#d3d3d3');
            break;
        
            case 1:
                $('.JobStatusHeader').css('background-color','#8080FF');
                $('#releaseJobtoAccounting').toggleClass('buttondkmgtpnl80');
            break;
        
            case 2:
                $('.JobStatusHeader').css('background-color','#8080FF');
                $('#releaseJobtoAccounting').toggleClass('buttondkmgtpnl80');
                $('#releaseJobtoAccounting').css('color','#FFFFFF');
            break;
        
            case 3:
                $('.JobStatusHeader').css('background-color','#d3d3d3');
            break;
        
            case 4:
                $('.JobStatusHeader').css('background-color','lightgreen');
                $('#releaseJobtoAccounting').toggleClass('buttondkgrnpnl80');
            break;
        
            case 5:
                $('.JobStatusHeader').css('background-color','lightgreen');
                $('#releaseJobtoAccounting').toggleClass('buttondkgrnpnl80');
            break;
        
            case 65535:
                $('#sendJobData').toggleClass('buttondkorgpnl80');
                $('#sendJobData').attr('disabled',true);
                $('#sendJobData').attr('title','Job Cost Missing and must be rebuilt for this Job. Click Reset Job to Rebuild.');
            break;
        
            default:
                $('.JobStatusHeader').css('background-color','#d3d3d3');
                $('#releaseJobtoAccounting').toggleClass('buttondkgrypnl80');
                $('#sendJobData').toggleClass('buttondkgrypnl80');
        }
    });
});