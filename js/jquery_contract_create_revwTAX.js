$(document).ready(function()
{
    //$('#createcontract').tabs();
    
    $('button').button();
    
    $('#AcceptSubmit').click(function()
    {
        if (parseInt($('#ps_calc').val()) == 0)
        {
            alert('Calculate Payment Schedule before continuing');            
        }
        else
        {
            var cc=$('#cdate').val();
            
            if (cc.length > 0)
            {
                $('#submitContract').submit();
            }
            else
            {
                alert('Contract Date must be filled in');
            }
        }
    });
    
    $('#psCalculate').click(function()
    {
        $('#ps_calc').val(1);
        $('#psCalcNotice').html('Calculated!');
    });
    
    $('#amt_501L').keyup(function()
    {
        $('#ps_calc').val(0);
        $('#psCalcNotice').html('<font color="red">Needs Calc!</font>');
    });
    
    $('#amt_531L').keyup(function()
    {
        $('#ps_calc').val(0);
        $('#psCalcNotice').html('<font color="red">Needs Calc!</font>');
    });

    $('#PSDWNinc').click(function()
    {
        $('#ps_calc').val(0);
        $('#psCalcNotice').html('<font color="red">Needs Calc!</font>');
    });
    
    $('#PSDWNdec').click(function()
    {
        $('#ps_calc').val(0);
        $('#psCalcNotice').html('<font color="red">Needs Calc!</font>');
    });
    
    $('#PSSECinc').click(function()
    {
        $('#ps_calc').val(0);
        $('#psCalcNotice').html('<font color="red">Needs Calc!</font>');
    });
    
    $('#PSSECdec').click(function()
    {
        $('#ps_calc').val(0);
        $('#psCalcNotice').html('<font color="red">Needs Calc!</font>');
    });

});