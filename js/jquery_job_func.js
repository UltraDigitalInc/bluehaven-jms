$(document).ready(function()
{
    var ajxaccscript    = 'subs/ajax_accounting_req.php';
    var ajxjobscript    = 'subs/ajax_job_req.php';
    var procspinnerIMG  = '<img src="images/mozilla_blu.gif"> Processing...';
    var spinnerIMG      = '<img src="images/mozilla_blu.gif">';
    var setStatus       = 0;
    
    $('#resetJobCostDataStatusPanel').hide();
	
	$('#sandcblock').live('change',function(e){
		var scbox=($(this).is(':checked'))?1:0;		
		$.post(ajxjobscript,
        {
            call : 'job',
            subq : 'set_SandC',
            usr_oid : $('#active_office').val(),
            usr_jobid : $('#usr_jobid').val(),
			usr_sandc: scbox,
            optype : 'table'
        });
	});
    
    $('#releaseJobtoAccounting').live('click',function() {
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
            //alert(data);
            switch (parseInt(data))
            {            
                case 1:
                    var agree = confirm('ATTENTION!\n\nYou are attempting remove the Accounting Release on this Job.\n\nClick OK to verify.\nClick CANCEL if you do not wish to make this change');
                
                    if (agree)
                    {
                        //alert('Release Removed');
                        $.ajax({
                            cache:false,
                            type : 'POST',
                            url : ajxaccscript,
                            dataType : 'html',
                            data: {
                                call : 'accountingsystem',
                                subq : 'set_JMS_Job_Status_from_Job',
                                usr_oid : $('#usr_oid').val(),
                                usr_jobid : $('#usr_jobid').val(),
                                usr_jadd : $('#usr_jadd').val(),
                                usr_jst : 0,
                                optype : 'table'
                            },
                            success : function(data){
                                $('.JobStatusHeader').css('background-color','#d3d3d3');
                                $('#releaseJobtoAccounting').css('background-color','gray');
                                $('#statusbox_AccountingStatus').html('<em>Unreleased</em>').show();
                            },
                            error : function(XMLHttpRequest, textStatus, errorThrown) {
                                $('#statusbox_AccountingStatus').html(textStatus).show();
                            }
                        });
                    }
                break;
            
                case 2:
                
                break;
            
                case 4:
                    $('.JobStatusHeader').css('background-color','lightgreen');
                    $('#releaseJobtoAccounting').toggleClass('buttondkgrnpnl80');
                    alert('This Job has already been Processed or Submitted for Processing and must be managed from the Quickbooks Module.');                
                break;
                
                default:
                    var agree = confirm('ATTENTION!\n\nYou are attempting to Release this Job to Accounting.\n\nClick OK to verify.\nClick CANCEL if you do not wish to make this change');
            
                    if (agree)
                    {
                        //alert('Released Agreed');
                        $.ajax({
                            cache:false,
                            type : 'POST',
                            url : ajxaccscript,
                            dataType : 'html',
                            data: {
                                call : 'accountingsystem',
                                subq : 'release_Job',
                                usr_oid : $('#usr_oid').val(),
                                usr_jobid : $('#usr_jobid').val(),
                                usr_jadd : $('#usr_jadd').val(),
                                optype : 'table'
                            },
                            success : function(data){
                                alert(data);
                                $('.JobStatusHeader').css('background-color','#8080FF');
                                $('#releaseJobtoAccounting').toggleClass('buttondkmgtpnl80');
                                $('#statusbox_AccountingStatus').html(data).show();
                            },
                            error : function(XMLHttpRequest, textStatus, errorThrown) {
                                $('#statusbox_AccountingStatus').html(textStatus).show();
                            }
                        });
                    }
                break;
            }
        });
        
        return false;
    });
    
    $('#usr_createjob').live('click',function()
    {
        var agree = confirm('ATTENTION!\n\nYou are attempting Release this Job to Accounting.\n\nClick OK to verify.\nClick CANCEL if you do not wish to make this change');
		
        if (agree)
        {
            //alert($('#usr_accountingrelease').val());
            return true;
        }
        
        return false;
    });
    
    $('#usr_findnextjobnumber').live('click',function()
    {
        $('#status_getjobnumber').html(spinnerIMG).show('slow').hide('slow');
        $.ajax({
            cache:false,
            type : 'POST',
            url : ajxjobscript,
            dataType : 'html',
            data: {
                call : 'job',
                subq : 'get_NextJobNumber',
                sys_oid : $('#sys_oid').val(),
                optype : 'table'
            },
            success : function(data){
                $('#usr_njobid').val(data);
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
                $('#statusbox_AccountingStatus').html(textStatus).show();
            }
        });
    });
    
    $('#usr_RevertToContract').live('click',function()
    {
        //alert($('#sys_oid').val());
        //alert($('#sys_jobid').val());
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
            //alert(data);

            if (data==0 || data==1 || data==2 || data==65535)
            {
                //alert('Released');
                var agree = confirm('ATTENTION!\n\nYou are attempting remove the Accounting Release on this Job.\n\nClick OK to verify.\nClick CANCEL if you do not wish to make this change');
    
                if (agree)
                {
                    $('#submit_RevertToContract').submit();
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                alert('This Job has already been processed through Accounting and cannot be Reverted');
                return false;
            }
        });
    });
    
    
    $('#resetJobCostData').live('click',function()
    {
        //alert($('#sys_oid').val());
        $.ajax({
            cache:false,
            type : 'POST',
            url : ajxaccscript,
            dataType : 'html',
            data: {
                call : 'accountingsystem',
                subq : 'list_JobCostData',
                proc_oid : $('#proc_oid').val(),
                proc_jobid : $('#proc_jobid').val(),
                optype : 'table'
            },
            success : function(data){
                $('#debug_dialog').dialog();
				$('#debug_dialog').html(data);
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
                $('#debug_dialog').dialog();
				$('#debug_dialog').html(textStatus);
            }
        });
    });
    
    $('#sendJobData').live('click',function()
    {
        var agree = confirm('ATTENTION!\n\nJob Cost must be rebuilt for this Job.\n\nClick OK to verify.\nClick CANCEL if you do not wish to make this change');
    
        if (agree)
        {
            $.ajax({
                cache:false,
                type : 'POST',
                url : ajxaccscript,
                dataType : 'html',
                data: {
                    call : 'accountingsystem',
                    subq : 'send_Job_Data',
                    usr_oid : $('#usr_oid_sj').val(),
                    usr_jobid : $('#usr_jobid_sj').val(),
                    usr_jadd : 0,
                    usr_jst : 0,
                    optype : 'table'
                },
                success : function(data){
                    alert('Processing: ' + data);
                    //$('.JobStatusHeader').css('background-color','#d3d3d3');
                    //$('#releaseJobtoAccounting').css('background-color','gray');
                    //$('#statusbox_AccountingStatus').html('<em>Unreleased</em>').show();
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    $('#statusbox_AccountingStatus').html(textStatus).show();
                }
            });
        }
    });
    
});