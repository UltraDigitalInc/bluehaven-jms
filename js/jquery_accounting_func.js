$(document).ready(function()
{
    var acc_tab_tab_id      = parseInt($.cookie("accounting_tab_tab")) || 0;
    var acc_queue_tab_id    = parseInt($.cookie("accounting_queues_tab")) || 0;
    var acct_oid            = $('#acct_OID').val();
    var ajxscript           = 'subs/ajax_accounting_req.php';
    var spinnerIMG          = '<img src="images/mozilla_blu.gif"> Retrieving...';
    var procspinnerIMG      = '<img src="images/mozilla_blu.gif"> Processing...';
    var spinner             = '<img src="images/mozilla_blu.gif">';
    
    //$("#get_Job_Queued").css("cursor", "pointer");

    $('#Accounting_Tab').tabs({
        cache:false,
        selected:acc_tab_tab_id,
        show:function(event,ui){
            var show_tab_id = ui.index;
            $.cookie("accounting_tab_tab", show_tab_id);
            
            switch(show_tab_id)
            {
                case 0:
                    $('#panel_JMS_Released').html(spinnerIMG).show(500);
					list_JMS_Released();
				break;
            
                case 1:
                    $('#panel_Log').html(spinnerIMG).show(500);
					list_Acct_Log($('#usr_qstat').val(),$('#usr_qact').val(),$('#usr_lcnt').val());
				break;
            }
        }
    });
    
    $('#Accounting_Queues').tabs({
        cache:false,
        selected:acc_queue_tab_id,
        show:function(event,ui){
            var show_tab_id = ui.index;
            $.cookie("accounting_queues_tab", show_tab_id);
            
            switch(show_tab_id)
            {
                case 0:
                    $('#panel_Queue_Pending').html(spinnerIMG).show(500);
					list_Queue_QB('q');
				break;
            
                case 1:
                    $('#panel_Queue_Incomplete').html(spinnerIMG).show(500);
                    list_Queue_QB('i');
				break;
            
                case 2:
                    $('#panel_Queue_Errors').html(spinnerIMG).show(500);
                    list_Queue_QB('e');
				break;
            }
        }
    });
    
    $('#releaseJobtoAccounting').live('click',function()
    {
        alert($(this).parent().children('.usr_jid').val());
        /*
        $.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
                call : 'user',
                subq : 'releaseJobtoAccounting',
                usr_sid : $('#usr_sid').val(),
                usr_asid : $('#usr_asid').val(),
                optype : 'table'
			},
			success : function(data){
				$('#panel_JMSProfilesInfo').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#panel_JMSProfilesInfo').html(textStatus).show(500);
			}
		});
        */
    });
    
    $('.send_Job_Package_to_Accounting').live('click',function()
    {
        //alert($(this).parent().children('.usr_jobid').val());
        //alert($(this).parent().children('.usr_cid').val());
        
        //$(this).parent().parent().parent().children('.proc_status_all').html(spinner).show(500);
        var agree = confirm('ATTENTION!\n\nYou are attempting to send a Job to the Accounting System.\n\nClick OK to verify.\nClick CANCEL if you do not wish to make this change');
		
		if (agree)
		{            
            $(this).parent().parent().parent().children('.proc_status_all').html(spinner).show(500);

            /*
            $.ajax({
                cache:false,
                type : 'POST',
                url : ajxscript,
                dataType : 'html',
                data: {
                    call : 'accountingsystem',
                    subq : 'send_Payment_Info',
                    usr_oid : acct_oid,
                    usr_jid : $(this).parent().children('.usr_cid').val(),
                    usr_qaction : 'ReceivePaymentAdd',
                    optype : 'table'
                },
                success : function(data){
                    //alert(data);
                    //$('#panel_JMS_Released').html(data).show(500);
                    //list_JMS_Released();
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    $('#panel_JMS_Released').html(textStatus).show(500);
                }
            });
            
            
            $.ajax({
                cache:false,
                type : 'POST',
                url : ajxscript,
                dataType : 'html',
                data: {
                    call : 'accountingsystem',
                    subq : 'send_Job_to_Accounting',
                    usr_oid : acct_oid,
                    usr_jid : $(this).parent().children('.usr_jid').val(),
                    usr_qaction : 'EstimateAdd',
                    optype : 'table'
                },
                success : function(data){
                    //alert(data);
                    //$('#panel_JMS_Released').html(data).show(500);
                    //list_JMS_Released();
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    $('#panel_JMS_Released').html(textStatus).show(500);
                }
            });
            */
            list_JMS_Released();
            return true;
		}
		else
		{
			return false;
		}
    });
    
    $('.clearAccountingState').live('click',function()
    {
        var agree = confirm('ATTENTION!\n\nYou are attempting to clear the current Queue state of this Job.\n\nClick OK to verify and return this job to a Queued state.\nClick CANCEL if you do not wish to make this change');
		
		if (agree)
		{
            //alert($(this).parent().children('.usr_qid').val());
            $('#panel_Accounting_Queues').html(spinnerIMG).show(500);
            $.ajax({
                cache:false,
                type : 'POST',
                url : ajxscript,
                dataType : 'html',
                data: {
                    call : 'accountingsystem',
                    subq : 'clear_Accounting_State',
                    usr_oid : $('#acct_OID').val(),
                    usr_qid : $(this).parent().children('.usr_qid').val(),
                    optype : 'table'
                },
                success : function(data){
                    //alert(data);
                    $('#get_Jobs_Queued').val('q');
                    $('#panel_Accounting_Queues').html(data).show(500);
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    //alert('Error');
                    $('#panel_Accounting_Queues').html(textStatus).show(500);
                }
            });
            
            return true;
		}
		else
		{
			return false;
		}
    });
    
    $('.clearAccountingState_Log').live('click',function()
    {
        var agree = confirm('ATTENTION!\n\nYou are attempting to clear the current Queue state of this Job.\n\nClick OK to verify and return this job to a Queued state.\nClick CANCEL if you do not wish to make this change');
		
		if (agree)
		{
            //alert($(this).parent().children('.usr_qid').val());
            $('#panel_Log').html(spinnerIMG).show(500);
            $.ajax({
                cache:false,
                type : 'POST',
                url : ajxscript,
                dataType : 'html',
                data: {
                    call : 'accountingsystem',
                    subq : 'clear_Accounting_State_Log',
                    usr_oid : $('#acct_OID').val(),
                    usr_qid : $(this).parent().children('.usr_qid').val(),
                    optype : 'table'
                },
                success : function(data){
                    $('#panel_Log').html(data).show(500);
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    $('#panel_Log').html(textStatus).show(500);
                }
            });
            
            list_Acct_Log($('#usr_qstat').val(),$('#usr_lcnt').val());
            
            return true;
		}
		else
		{
			return false;
		}
    });
    
    $('.delete_from_Accounting_Log').live('click',function()
    {
        //alert('Click');
        var agree = confirm('ATTENTION!\n\nYou are attempting to delete this Log entry.\n\nClick OK to verify.\nClick CANCEL if you do not wish to make this change');
		
		if (agree)
		{
            //alert($(this).parent().children('.usr_qid').val());
            $('#panel_Log').html(spinnerIMG).show(500);
            $.ajax({
                cache:false,
                type : 'POST',
                url : ajxscript,
                dataType : 'html',
                data: {
                    call : 'accountingsystem',
                    subq : 'delete_from_Accounting_Log',
                    usr_oid : $('#acct_OID').val(),
                    usr_qid : $(this).parent().children('.usr_qid').val(),
                    optype : 'table'
                },
                success : function(data){
                    //alert(data);
                    //$('#panel_Log').html(data).show(500);
                    list_Acct_Log($('#get_Log_Count').val());
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    //alert('Error');
                    $('#panel_Log').html(textStatus).show(500);
                }
            });
            
            return true;
		}
		else
		{
            //alert('Rejected');
			return false;
		}
    });
    
    $('.set_JMS_Job_Status').live('click',function()
    {
        //alert($(this).parent().children('.usr_jobid').val());
        if ($(this).parent().children('.usr_jst').val() == 0)
        {
            var agree = confirm('ATTENTION!\n\nYou are attempting to remove this Job from the Released Queue.\n\nClick OK to verify and return this job to a Queued state.\nClick CANCEL if you do not wish to make this change');
        }
        else
        {
            var agree = confirm('ATTENTION!\n\nYou are attempting change the state of this Job.\n\nClick OK to verify.\nClick CANCEL if you do not wish to make this change');
        }
		
		if (agree)
		{
            //alert($(this).parent().children('.usr_jid').val());
            //alert($(this).parent().children('.usr_jst').val());
            $('#panel_JMS_Released').html(spinnerIMG).show(500);
            $.ajax({
                cache:false,
                type : 'POST',
                url : ajxscript,
                dataType : 'html',
                data: {
                    call : 'accountingsystem',
                    subq : 'set_JMS_Job_Status',
                    usr_oid : $('#acct_OID').val(),
                    usr_jid : $(this).parent().children('.usr_jid').val(),
                    usr_jst : $(this).parent().children('.usr_jst').val(),
                    optype : 'table'
                },
                success : function(data){
                    //alert(data);
                    //$('#get_Jobs_Queued').val('q');
                    $('#panel_JMS_Released').html(data).show(500);
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    //alert('Error');
                    $('#panel_JMS_Released').html(textStatus).show(500);
                }
            });
            return true;
		}
		else
		{
            //alert('Rejected');
			return false;
		}
    });
    
    $('.revertJobtoJMSReleased').live('click',function()
    {
        //alert($(this).parent().children('.usr_qid').val());
        var agree = confirm('ATTENTION!\n\nYou are attempting to remove a Job from the Processing Queue.\n\nClick OK to return the Job to the Released Queue.\nClick CANCEL if you do not wish to make this change');
		
		if (agree)
		{
            //alert($(this).parent().children('.usr_jid').val());
            $('#panel_Accounting_Queues').html(procspinnerIMG).show(500);
            //$('#panel_Accounting_Status').hide(500);
            $.ajax({
                cache:false,
                type : 'POST',
                url : ajxscript,
                dataType : 'html',
                data: {
                    call : 'accountingsystem',
                    subq : 'revert_Job_to_JMS_Released',
                    usr_oid : $('#acct_OID').val(),
                    usr_qid : $(this).parent().children('.usr_qid').val(),
                    optype : 'table'
                },
                success : function(data){
                    //alert('Success');
                    //$.cookie("accounting_tab_tab", 0);
                    //$('#panel_Accounting_Queues').html(data).show(500);
                    list_JMS_Released();
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    $('#panel_Accounting_Queues').html(textStatus).show(500);
                }
            });

            return true;
		}
		else
		{
            //alert('Rejected');
			return false;
		}

    });
    
    $('.resendJobtoAccounting').live('click',function()
    {
        var agree = confirm('ATTENTION!\n\nYou are attempting to resend a Job to the Accounting System.\n\nClick OK to verify.\nClick CANCEL if you do not wish to make this change');
		
		if (agree)
		{
            //alert($(this).parent().children('.usr_jid').val());
            $('#panel_Accounting_Status').html(procspinnerIMG).show(500);
            $.ajax({
                cache:false,
                type : 'POST',
                url : ajxscript,
                dataType : 'html',
                data: {
                    call : 'accountingsystem',
                    subq : 'resend_Job_to_Accounting',
                    usr_oid : $('#acct_OID').val(),
                    usr_jid : $(this).parent().children('.usr_jid').val(),
                    optype : 'table'
                },
                success : function(data){
                    $('#panel_Accounting_Status').hide();
                    $('#panel_JMS_Released').html(data).show(500);
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    $('#panel_Accounting_Status').hide();
                    $('#panel_JMS_Released').html(textStatus).show(500);
                }
            });

            return true;
		}
		else
		{
            alert('Rejected');
			return false;
		}
    });
    
    $('#select_CustomerYear').live('change',function()
    {
        list_JMS_Released();
    });
    
    $('#update_list_JMS_Released').live('click',function()
    {
        list_JMS_Released();
    });
    
    function get_rel_Year()
    {
        $('#select_CustomerYear').live('change',function()
        {
            return $('#select_CustomerYear').val();
        });
    }
    
    function list_JMS_Released()
	{
        //alert($('#select_CustomerYear').val());
        $('#panel_JMS_Released').html(spinnerIMG).show(500);
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'accountingsystem',
			   subq : 'list_JMS_Released',
			   oid : acct_oid,
               yr: $('#select_CustomerYear').val(),
			   optype : 'table'
			},
			success : function(data){
				$('#panel_JMS_Released').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#panel_JMS_Released').html(textStatus).show(500);
			}
		});
		
		return true;
	}
    
    function list_JMS_Closed(c)
	{
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'accountingsystem',
			   subq : 'list_JMS_Closed',
			   usr_oid : acct_oid,
               usr_cnt : c,
			   optype : 'table'
			},
			success : function(data){
				$('#panel_JMS_Closed').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#panel_JMS_Closed').html(textStatus).show(500);
			}
		});
		
		return true;
	}
    
    //$('#get_Jobs_Queued').live('click',function()
    //{
    //    list_Queue_QB('q');
    //});
    
    $('#get_Jobs_Queued').live('change',function()
    {
        list_Queue_QB($('#get_Jobs_Queued').val());
        //alert($('#get_Jobs_Queued').val());
    });
    
    $('#get_Jobs_Incomplete').live('click',function()
    {
        list_Queue_QB('i');
    });
    
    $('#get_Jobs_Errors').live('click',function()
    {
        list_Queue_QB('e');
    });
    
    //$('#get_Log_Count').live('click',function()
    //{
    //    list_Acct_Log($('#usr_qstat').val(),$('#usr_lcnt').val());
    //});
    
    $('#usr_qact').live('change',function()
    {
        list_Acct_Log($('#usr_qstat').val(),$('#usr_qact').val(),$('#usr_lcnt').val());
    });
    
    $('#usr_qstat').live('change',function()
    {
        list_Acct_Log($('#usr_qstat').val(),$('#usr_qact').val(),$('#usr_lcnt').val());
    });
    
    $('#usr_lcnt').live('change',function()
    {
        list_Acct_Log($('#usr_qstat').val(),$('#usr_qact').val(),$('#usr_lcnt').val());
    });
    
    $('#update_list_Acct_Log').live('click',function()
    {
        list_Acct_Log($('#usr_qstat').val(),$('#usr_qact').val(),$('#usr_lcnt').val());
    });
    
    $('#get_Closed_Count').live('change',function()
    {
        list_JMS_Closed($('#get_Closed_Count').val());
    });
    
    function list_Queue_QB(xstat)
	{
        $('#panel_Accounting_Queues').html(spinnerIMG).show(500);
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'accountingsystem',
			   subq : 'list_Queue_QB',
			   oid : acct_oid,
               qstat : xstat,
			   optype : 'table'
			},
			success : function(data){
                $('#panel_Accounting_Queues').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
                $('#panel_Accounting_Queues').html(textStatus).show(500);
			}
		});
		
		return true;
	}
    
    function list_Queue_Processed()
	{
        $('#panel_Queue_Processed').html(spinnerIMG).show(500);
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'accountingsystem',
			   subq : 'list_Processed_QB',
			   oid : acct_oid,
			   optype : 'table'
			},
			success : function(data){
				$('#panel_Queue_Processed').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#panel_Queue_Processed').html(textStatus).show(500);
			}
		});
		
		return true;
	}
    
    function list_Queue_All()
	{
        $('#panel_Queue_All').html(spinnerIMG).show(500);
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'accountingsystem',
			   subq : 'list_Queue_All',
			   oid : acct_oid,
			   optype : 'table'
			},
			success : function(data){
				$('#panel_Queue_All').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#panel_Queue_All').html(textStatus).show(500);
			}
		});
		
		return true;
	}
    
    function list_Acct_Log(q,a,d)
	{
        $('#panel_Log').html(spinnerIMG).show(500);
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'accountingsystem',
			   subq : 'list_Log',
			   usr_oid : acct_oid,
               usr_qstat : q,
               usr_qact : a,
               usr_lcnt : d,
			   optype : 'table'
			},
			success : function(data){
				$('#panel_Log').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#panel_Log').html(textStatus).show(500);
			}
		});
		
		return true;
	}
});