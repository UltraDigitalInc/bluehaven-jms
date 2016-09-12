$(document).ready(function()
{
    var acct_oid            = $('#acct_OID').val();
    var acct_cid            = $('#usr_cid').val();
    var accscript           = 'subs/ajax_accounting_req.php';
    var jobscript           = 'subs/ajax_job_req.php';
    var prcscript           = 'QB/bhsoap/QB_Process_PID.php';
    var spinnerIMGonly      = '<img src="images/mozilla_blu.gif">';
    var spinnerIMG          = '<img src="images/mozilla_blu.gif">';
    var procspinnerIMG      = '<img src="images/mozilla_blu.gif"> Processing...';
    
    $('#customer_onesheet').accordion({ active:0,clearStyle: true,collapsible:false });
    $('#comments_onesheet').accordion({ active:1,clearStyle: true,collapsible:false });
    $('#cconstruction_content').tabs();
    
    if ($('#LeadCommentList').length)
    {
        getLeadCommentList();
    }
    
    $('#refreshLeadComments').live('click',function(){
        getLeadCommentList();
    });
    
    if ($('#OneSheetCmntSelector').length)
    {
        var sget=getOneSheetCmntSelector();
        displayOCSSelect(sget);
    }
    
    $('.setpointer').live('hover',function() {
            $(this).css('cursor','pointer');
        }, function() {
            $(this).css('cursor','auto');
        return false;
    });
    
    $('.texpandtext').live('click',function(){
        $(this).hide();
        $(this).parent().children('span.thiddentext').show();
    });
    
    $('#expandLeadComments').live('click',function(){
        $('span.texpandtext').hide();
        $('span.thiddentext').show();
    });
    
    $('.datepick').datepicker();
    
    $('#d1').datepicker();
    $('#d2').datepicker();
    $('#d3').datepicker();
    
    $('#datep1').datepicker();
    $('#datep2').datepicker();
    $('#datep3').datepicker();
    $('#datep4').datepicker();
    $('#datep5').datepicker();
    $('#datep6').datepicker();
    $('#datep7').datepicker();
    $('#datep8').datepicker();
    $('#datep9').datepicker();
    $('#datep10').datepicker();
    $('#datep11').datepicker();
    $('#datep12').datepicker();
    $('#datep13').datepicker();
    $('#datep14').datepicker();
    $('#datep15').datepicker();
    $('#datep16').datepicker();
    $('#datep17').datepicker();
    $('#datep18').datepicker();
    $('#datep19').datepicker();
    $('#datep20').datepicker();
    $('#datep21').datepicker();
    $('#datep22').datepicker();
    $('#datep23').datepicker();
    $('#datep24').datepicker();
    $('#datep25').datepicker();
    $('#datep26').datepicker();
    $('#datep27').datepicker();
    $('#datep28').datepicker();
    $('#datep29').datepicker();
    $('#datep30').datepicker();
    $('#datep31').datepicker();
    $('#datep32').datepicker();
    $('#datep33').datepicker();
    $('#datep34').datepicker();
    $('#datep35').datepicker();
    $('#datep36').datepicker();
    $('#datep37').datepicker();
    $('#datep38').datepicker();
    $('#datep39').datepicker();
    $('#datep40').datepicker();
    
    $('#refreshLeadComments').live('click',function(){
        getLeadCommentList();
    });
    
    $('#saveLeadComment').live('click',function(event){
        event.preventDefault();
        saveLeadComment();
    });
    
    function saveLeadComment()
    {
        var cid=parseInt($('#usr_cid').val());
        var ac=$('#mtext').val();
        var cf=$('#cmntflag').val();
        
        if (!isNaN(cid) && ac.length > 0)
        {
            $.ajax({
                cache:false,
                type : 'POST',
                url : 'subs/ajax_leads_req.php',
                dataType : 'json',
                data: {
                    call : 'leads',
                    subq : 'save_LeadComment',
                    sysCID : cid,
                    cmnt : ac,
                    cmntflag : cf,
                    optype : 'json'
                },
                success : function(data){
                    if (parseInt(data)!=0)
                    {
                       $('#mtext').val('');
                       getLeadCommentList();
                    }
                    else
                    {
                       alert('ERROR:\nYour Comment did not save properly.\nContact Support if this error persists.');
                    }
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    $('#LeadCommentList').html(textStatus).show();
                }
            });
           return true;
        }
        else
        {
           alert('Type not Select or No Comment Text');
           return false;
        }
    }
    
    function getLeadCommentList()
    {
        var cid=parseInt($('#usr_cid').val());
        var sget=getOneSheetCmntSelector();
        displayOCSSelect(sget);
        
        if (cid!=0)
        {
            $.ajax({
                cache:false,
                type : 'GET',
                url : 'subs/ajax_leads_req.php',
                dataType : 'html',
                data: {
                    call : 'leads',
                    subq : 'get_LeadCommentList',
                    sysCID : cid,
                    optype : 'table'
                },
                success : function(data){
                    $('#LeadCommentList').html(data);
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    $('#LeadCommentList').html(textStatus).show();
                }
            });
        }
    }
    
    function getOneSheetCmntSelector()
    {
        return $.ajax({
            type : 'GET',
            url : 'subs/ajax_leads_req.php',
            dataType : 'html',
            data: {
                call : 'leads',
                subq : 'get_OneSheetCmntSelector',
                usr_cid : parseInt($('#usr_cid').val()),
                optype : 'table'
            }
        });
    }
    
    function displayOCSSelect(x)
    {
        x.success(function(realData) {
            $('#OneSheetCmntSelector').html(realData);
        });
    }
    
    function get_Customer_LifeCycle(oid,cid)
    {
        $('#CustomerLifeCycle').html(spinnerIMG).show(500);
        $.ajax({
            cache:false,
            type : 'POST',
            url : accscript,
            dataType : 'html',
            data: {
                call : 'accountingsystem',
                subq : 'get_CustomerLifeCycle',
                usr_oid : oid,
                usr_cid : cid,
                optype : 'table'
            },
            success : function(data){
                $('#CustomerLifeCycle').html(data).show(500);
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
                alert(textStatus);
            }
        });
        
        return true;
    }
    
    function refresh_Customer_LifeCycle(oid,cid)
    {
        setInterval('get_Customer_LifeCycle('+ oid +','+ cid +')', 600);
    }
    
    function send_Customer_Info(oid,cid)
    {
        $.ajax({
            cache:false,
            type : 'POST',
            url : accscript,
            dataType : 'html',
            data: {
                call : 'accountingsystem',
                subq : 'send_Customer_Info_by_CID',
                usr_oid : oid,
                usr_cid : cid,
                usr_qact : 'CustomerAdd',
                optype : 'table'
            },
            success : function(data){               
                //alert(data);
                alert('Customer Information Queued!');
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
                //$('#panel_JMS_Released').html(textStatus).show(500);
            }
       });
        
        return true;
    }
    
    $('#editContractDate').click(function()
    {
        show_editContractDateDialog();
    });
    
    function show_editContractDateDialog()
    {
        var dialogTriggerEl='<div id="editContractDateWorkSpace"></div>';
        $('#editContractDate').append(dialogTriggerEl);
        
        $("#editContractDateWorkSpace").dialog({
            modal: true,
            title: 'Edit Contract Date',
            closeText: 'Close',
            buttons: {
                "Update": function()
                {
                    submit_EditContractDate();
                },
                "Cancel": function(){
                    $(this).dialog('close');
                }
            },
            close: function(){
                $('#editContractDateWorkSpace').remove();
            }
        });
        
        $('#editContractDateWorkSpace').append('<input type="text" id="editContractDateActual"><br>');
        $('#editContractDateWorkSpace').css('text-align','center');
        $('#editContractDateActual').css('text-align','center');
        
        $('#editContractDateActual').val($('#editContractDate').html());
        $('#editContractDateActual').datepicker();
    }
    
    function submit_EditContractDate()
    {
        var ContractDate = new Date($('#editContractDateActual').val());
        
        if (ContractDate)
        {
            $.ajax({
                cache : false,
                type : 'POST',
                url : jobscript,
                dataType : 'html',
                data : {
                    call : 'job',
                    subq : 'checkContractDate',
                    usr_oid : acct_oid,
                    usr_cid : acct_cid,
                    usr_NewContractDate : $('#editContractDateActual').val(),
                    optype : 'table'
                },
                success : function(data){
                    if (parseInt(data)==0 || parseInt(data)==1)
                    {
                        $('#editContractDate').text($('#editContractDateActual').val());
                        $('#editContractDateWorkSpace').dialog('close');
                        $('#editContractDateWorkSpace').remove();
                    }
                    else
                    {
                        $('#editContractDateWorkSpace').append(data);
                    }
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    $('#editContractDateWorkSpace').append(textStatus);
                }
            });
        }
    }
    
    $('#send_CustomerInfo').live('click',function()
    {
        var agree = confirm('ATTENTION!\n\nYou are attempting to send Customer Information to Quickbooks.\n\nClick OK to verify.\nClick CANCEL if you do not wish to make this change');
     
        if (agree)
        {
           $('#update_status_Customer').html(spinnerIMGonly).show(500);
           $.ajax({
                cache:false,
                type : 'POST',
                url : accscript,
                dataType : 'html',
                data: {
                    call : 'accountingsystem',
                    subq : 'send_Customer_Info_by_CID',
                    usr_oid : acct_oid,
                    usr_cid : acct_cid,
                    usr_qact : 'CustomerAdd',
                    optype : 'table'
                },
                success : function(data){
                    $('#CustomerCount').html(data).show(500);
                    $('#update_status_Customer').hide(500);
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    //$('#panel_JMS_Released').html(textStatus).show(500);
                }
           });
           
           return true;
        }
        else
        {
           return false;
        }
    });
    
    $('#send_PaymentInfo').live('click',function()
    {
        var CListID;
        
        $.ajax({
            cache:false,
            type : 'POST',
            url : accscript,
            dataType : 'html',
            data: {
                call : 'accountingsystem',
                subq : 'get_Customer_Status',
                usr_cid : $('#usr_cid').val(),
                optype : 'table'
            },
            success : function(data){
                //alert(data);
                if (data=='0')
                {
                    //alert('Not Exists');
                    alert('Customer Info must be processed into Quickbooks before Payments can be accepted');
                }
                else
                {
                    //alert('Exists');
                    var agree = confirm('ATTENTION!\n\nYou are attempting to send Payment Information to Quickbooks.\n\nClick OK to verify.\nClick CANCEL if you do not wish to send');
 
                    if (agree)
                    {
                        $('#update_status_Payments').html(spinnerIMGonly).show(500);
                        $.ajax({
                            cache:false,
                            type : 'POST',
                            url : accscript,
                            dataType : 'html',
                            data: {
                                call : 'accountingsystem',
                                subq : 'send_Payment_Info',
                                usr_oid : acct_oid,
                                usr_cid : acct_cid,
                                usr_qact : 'ReceivePaymentAdd',
                                optype : 'table'
                            },
                            success : function(data){
                                $('#PaymentCount').html(data).show(500);
                                $('#update_status_Payments').hide(500);
                            },
                            error : function(XMLHttpRequest, textStatus, errorThrown) {
                                alert(textStatus);
                            }
                        });
                        
                        return true;
                    }
                }
                
                return false;
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
                alert(textStatus);
            }
        });
        
        return false;
    });
    
    $('#send_InvoiceInfo').live('click',function()
    {
        var CListID;
        
        $.ajax({
            cache:false,
            type : 'POST',
            url : accscript,
            dataType : 'html',
            data: {
                call : 'accountingsystem',
                subq : 'get_Customer_Status',
                usr_cid : $('#usr_cid').val(),
                optype : 'table'
            },
            success : function(data){
                if (data=='0')
                {
                    alert('Customer Info must be processed into Quickbooks before Invoices can be accepted');
                }
                else
                {
                    var agree = confirm('ATTENTION!\n\nYou are attempting to send Invoices (Payment Schedule) to Quickbooks.\n\nClick OK to verify.\nClick CANCEL if you do not wish to send');
 
                    if (agree)
                    {
                        $('#update_status_Invoices').html(spinnerIMGonly).show(500);
                        $.ajax({
                            cache:false,
                            type : 'POST',
                            url : accscript,
                            dataType : 'html',
                            data: {
                                call : 'accountingsystem',
                                subq : 'send_Invoice_Info',
                                usr_oid : acct_oid,
                                usr_cid : acct_cid,
                                usr_qact : 'InvoiceAdd',
                                optype : 'table'
                            },
                            success : function(data){
                                $('#InvoiceCount').html(data).show(500);
                                $('#update_status_Invoices').hide(500);
                            },
                            error : function(XMLHttpRequest, textStatus, errorThrown) {
                                alert(textStatus);
                            }
                        });
                        
                        $('#update_status_Invoices').hide(500);
                        return true;
                    }
                }
                
                return false;
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
                alert(textStatus);
            }
        });
        
        return false;
    });
    
    $('#send_ContractInfo').live('click',function()
    {
        var CListID;
        
        $.ajax({
            cache:false,
            type : 'POST',
            url : accscript,
            dataType : 'html',
            data: {
                call : 'accountingsystem',
                subq : 'get_Customer_Status',
                usr_cid : acct_oid,
                optype : 'table'
            },
            success : function(data){
                //alert(data);
                if (data=='0')
                {
                    alert('Customer Info must be processed into Quickbooks before Invoices can be accepted');
                }
                else
                {
                    var agree = confirm('ATTENTION!\n\nYou are attempting to send Contract Info to Quickbooks.\n\nClick OK to verify.\nClick CANCEL if you do not wish to send');
 
                    if (agree)
                    {
                        //alert('Agree');
                        $.ajax({
                            cache:false,
                            type : 'POST',
                            url : accscript,
                            dataType : 'html',
                            data: {
                                call : 'accountingsystem',
                                subq : 'send_Contract_Info',
                                usr_oid : acct_oid,
                                usr_cid : acct_cid,
                                usr_qact : 'EstimateAdd',
                                optype : 'table'
                            },
                            success : function(data){
                                if (data=='0')
                                {
                                    alert('Contract NOT Queued');
                                }
                                else
                                {
                                    alert('Contract Queued');
                                }
                            },
                            error : function(XMLHttpRequest, textStatus, errorThrown) {
                                alert(textStatus);
                            }
                        });
                        
                        return true;
                    }
                }
                
                return false;
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
                alert(textStatus);
            }
        });
        
        return false;
    });
    
    function send_Invoices(oid,cid)
    {
        return $.ajax({
            cache:false,
            async:false,
            type : 'POST',
            url : accscript,
            dataType : 'html',
            data: {
                call : 'accountingsystem',
                subq : 'send_Invoice_Info',
                usr_oid : oid,
                usr_cid : cid,
                usr_qact : 'InvoiceAdd',
                optype : 'table'
            }
        }).responseText
    }
    
    function send_Payments(oid,cid)
    {
        return $.ajax({
            cache:false,
            async:false,
            type : 'POST',
            url : accscript,
            dataType : 'html',
            data: {
                call : 'accountingsystem',
                subq : 'send_Payment_Info',
                usr_oid : oid,
                usr_cid : cid,
                usr_qact : 'ReceivePaymentAdd',
                optype : 'table'
            }
        }).responseText
    }    
});