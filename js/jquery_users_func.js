$(document).ready(function()
{
    var user_view_tab_id   = parseInt($.cookie("user_view_menu_tab")) || 0;
    var ajxscript	= 'subs/ajax_user_req.php';
    var qbprcscript	= 'qb/bhsoap/QB_Process_PID.php';
	var spinnerIMG	= '<img src="images/mozilla_blu.gif"> Retrieving...';
	var updateTEXT = '<em>Updating....</em>';
    var updateHTML = '<img src="images/mozilla_blu.gif"> Updating...';
    var processIMG	= '<img src="images/mozilla_blu.gif"> Processing...';
    
    $('#submituserupdate').click(
        function()
        {
            $('#UpdateUserForm').submit();
        }
    )
    
    $('#usr_hnewcommdate').live('datepicker',function(){});
    
    $('#ViewUserInfo').tabs({
        cache:false,
        selected:user_view_tab_id,
        spinner:'Retrieving data...',
        show:function(event,ui){
            var show_tab_id = ui.index;
            $.cookie("user_view_menu_tab", show_tab_id);
            
            switch(show_tab_id)
            {
                case 0:
                    $('#panel_JMSUserInfo').html(spinnerIMG).show(500);
					show_JMSUser();
				break;
                
                case 1:
                    $('#panel_JMSSecurityInfo').html(spinnerIMG).show(500);
					show_JMSSecurity();
				break;
            
                case 2:
                    $('#panel_JMSFunctionalInfo').html(spinnerIMG).show(500);
					show_JMSFunctional();
				break;
            
                case 3:
                    $('#panel_JMSProfilesInfo').html(spinnerIMG).show(500);
					show_JMSProfiles();
				break;
            
                case 4:
                    $('#panel_JMSSalesRepInfo').html(spinnerIMG).show(500);
					show_JMSSalesRep();
				break;
                
                case 5:
					show_MASAccounting();
				break;
            
                case 6:
                    $('#panel_QBSAccountingInfo').html(spinnerIMG).show(500);
					show_QBSAccounting();
				break;
            
                case 7:
                    $('#panel_JMSAltOfficeAccessInfo').html(spinnerIMG).show(500);
					show_JMSAltOfficeAccess();
				break;
            
                case 8:
                    $('#panel_JMSUserSysInfo').html(spinnerIMG).show(500);
					show_JMSUserSysInfo();
                break;
            }
        }
    });
    
    function update_alt_security(oid,sid,mod,lvl)
    {
        if (mod=='S' && lvl==0)
        {
            var agree  = confirm('You are setting the User Access into this Office to Zero.\nThis will delete the security record and this user will no longer have access to this office.\n\nClick Ok to proceed. Click Cancel to abort.');
          
            if (agree)
            {
                //alert('Proceeding');
                $('#panel_JMSAltOfficeAccessInfo').html(updateHTML).show(500);
                $.ajax({
                    cache:false,
                    type : 'POST',
                    url : ajxscript,
                    dataType : 'html',
                    data: {
                       call : 'user',
                       subq : 'delete_Alt_Security',
                       oid : oid,
                       sid : sid
                    },
                    success : function(data){
                        //alert(data);
                        show_JMSAltOfficeAccess();
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                        //$('#status_accounting_qbs').html(textStatus).show(500);
                    }
                });
            }
        }
        else
        {
            $('#alt_security_update_status').html(updateHTML).show(500);
            
            $.ajax({
                cache:false,
                type : 'POST',
                url : ajxscript,
                dataType : 'html',
                data: {
                   call : 'user',
                   subq : 'update_Alt_Security',
                   oid : oid,
                   sid : sid,
                   mod : mod,
                   lvl : lvl
                },
                success : function(data){
                    $('#alt_security_update_status').hide(500);
                    //alert(data);
                    //show_JMSAltOfficeAccess();
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    //$('#status_accounting_qbs').html(textStatus).show(500);
                }
            });
            
            //$('#alt_security_update_status').hide(500);
            
        }
        
        return;
    }
    
    $('.security_module_select').live('change',function()
    {
        var oid=$(this).parent().children('.security_module_oid').val();
        var sid=$('#UserSID').val();
        var mod=$(this).parent().children('.security_module_type').val();
        var lvl=$(this).val();
        
        update_alt_security(oid,sid,mod,lvl);
    });
    
    function add_alt_security(oid,sid,lvls)
    {
        //alert(oid + ' : ' + uid + ' : ' + lvls[0] + ' : ' + lvls[1] + ' : ' + lvls[2] + ' : ' + lvls[3] + ' : ' + lvls[4] + ' : ' + lvls[5] + ' : ' + lvls[6]);
        $('#panel_JMSAltOfficeAccessInfo').html(updateHTML).show(500);
        $.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
               call : 'user',
			   subq : 'insert_Alt_Security',
			   oid : oid,
               sid : sid,
			   "level[E]" : lvls[0],
               "level[C]" : lvls[1],
               "level[J]" : lvls[2],
               "level[L]" : lvls[3],
               "level[R]" : lvls[4],
               "level[M]" : lvls[5],
               "level[S]" : lvls[6]
			},
			success : function(data){
                //alert(data);
				show_JMSAltOfficeAccess();
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#status_accounting_qbs').html(textStatus).show(500);
			}
		});
    }
    
    $('#submit_add_alt_security_profile').live('click',function()
    {
        baseval=0;
        
        if ($('#add_security_oid').val() != 0)
        {
            if ($('#security_module_select_S').val() != 0)
            {
                var oid=$('#add_security_oid').val();
                var sid=$('#UserSID').val();
                
                var mySets = new Array();
                mySets[0]=$('#security_module_select_E').val();
                mySets[1]=$('#security_module_select_C').val();
                mySets[2]=$('#security_module_select_J').val();
                mySets[3]=$('#security_module_select_L').val();
                mySets[4]=$('#security_module_select_R').val();
                mySets[5]=$('#security_module_select_M').val();
                mySets[6]=$('#security_module_select_S').val();
                
                //alert(oid + ' : ' + uid + ' : ' + val_E + ' : ' + val_C + ' : ' + val_J + ' : ' + val_L + ' : ' + val_R + ' : ' + val_M + ' : ' + val_S);
                add_alt_security(oid,sid,mySets);
            }
            else
            {
                alert('System cannot be set to 0');
            }
        }
        else
        {
            alert('Error! You must select an Office.');
        }
    });
    
    $('#replicate_home_office_profile').live('change',function()
    {
        if ($('#home_office').length)
        {
            baseval=0;
            
            if ($('#replicate_home_office_profile').is(':checked'))
            {
                var val_E=$("#home_office > table > tbody > tr > td > div > input.security_module_type[value='E']").parent().children('.security_module_select').val();
                var val_C=$("#home_office > table > tbody > tr > td > div > input.security_module_type[value='C']").parent().children('.security_module_select').val();
                var val_J=$("#home_office > table > tbody > tr > td > div > input.security_module_type[value='J']").parent().children('.security_module_select').val();
                var val_L=$("#home_office > table > tbody > tr > td > div > input.security_module_type[value='L']").parent().children('.security_module_select').val();
                var val_R=$("#home_office > table > tbody > tr > td > div > input.security_module_type[value='R']").parent().children('.security_module_select').val();
                var val_M=$("#home_office > table > tbody > tr > td > div > input.security_module_type[value='M']").parent().children('.security_module_select').val();
                var val_S=$("#home_office > table > tbody > tr > td > div > input.security_module_type[value='S']").parent().children('.security_module_select').val();
            }
            else
            {
                var val_E=baseval;
                var val_C=baseval;
                var val_J=baseval;
                var val_L=baseval;
                var val_R=baseval;
                var val_M=baseval;
                var val_S=baseval;
            }
            
            //alert(val_E + ' : ' + val_C + ' : ' + val_J + ' : ' + val_L + ' : ' + val_R + ' : ' + val_M + ' : ' + val_S);

            $('#security_module_select_E').val(parseInt(val_E));
            $('#security_module_select_C').val(parseInt(val_C));
            $('#security_module_select_J').val(parseInt(val_J));
            $('#security_module_select_L').val(parseInt(val_L));
            $('#security_module_select_M').val(parseInt(val_M));
            $('#security_module_select_R').val(parseInt(val_R));
            $('#security_module_select_S').val(parseInt(val_S));
        }
    });
    
    $('#submit_sendEmployeeConfig').live('click',function()
    {        
        $.ajax({
			cache:false,
			type : 'POST',
			url : qbprcscript,
			dataType : 'html',
			data: {
			   qact : 'EmployeeAdd',
			   oid : $('#UserOID').val(),
			   'pid[]' : $('#UserSID').val()
			},
			success : function(data){
				//$('#status_accounting').html(data).show();
                //var emsend = data;
                //$('#status_accounting_qbs').text(data);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#status_accounting_qbs').html(textStatus).show(500);
			}
		});
        
        if (parseInt($('#sys_srep').val())==1)
        {
            $.ajax({
                cache:false,
                type : 'POST',
                url : qbprcscript,
                dataType : 'html',
                data: {
                   qact : 'SalesRepAdd',
                   oid : $('#UserOID').val(),
                   'pid[]' : $('#UserSID').val()
                },
                success : function(data){
                    //$('#status_accounting').html(data).show();
                    //var srsend = data;
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    $('#status_accounting_qbs').html(textStatus).show(500);
                }
            });
        }
        
        //alert(parseInt($('#sys_srep').val()));
        $('#status_accounting_qbs').text('Request Queued. Requests are processed at 5 minute intervals.');
        //$('#status_accounting_qbs').text(emsend);
        //$('#status_accounting_qbs').html(emsend + srsend);
        //alert('Sent!');
    });
    
    function show_JMSUser()
	{
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'user',
			   subq : 'get_JMSUserInfo',
			   sid : $('#UserSID').val(),
			   optype : 'table'
			},
			success : function(data){
				$('#panel_JMSUserInfo').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#panel_JMSUserInfo').html(textStatus).show(500);
			}
		});
		
		return true;
	}
    
    $('#submit_updateJMSUserInfo').live('click',function()
    {        
        $.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'user',
			   subq : 'update_JMSUserInfo',
			   usr_sid : $('#usr_sid').val(),
               usr_fname : $('#usr_fname').val(),
               usr_lname : $('#usr_lname').val(),
               usr_phone : $('#usr_phone').val(),
               usr_extn : $('#usr_extn').val(),
               usr_sidm : $('#usr_sidm').val(),
               usr_stitle : $('#usr_stitle').val(),
               usr_assistant : $('#usr_assistant').val(),
               usr_hdate : $('#usr_hdate').val(),
               usr_email : $('#usr_email').val(),
			   optype : 'table'
			},
			success : function(data){
				$('#panel_JMSUserInfo').html(updateHTML).show();
                show_JMSUser();
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#panel_JMSUserInfo').html(textStatus).show(500);
			}
		});
    });
    
    function show_JMSSecurity()
	{
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'user',
			   subq : 'get_JMSSecurityInfo',
			   sid : $('#UserSID').val(),
			   optype : 'table'
			},
			success : function(data){
				$('#panel_JMSSecurityInfo').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#panel_JMSSecurityInfo').html(textStatus).show(500);
			}
		});
		
		return true;
	}
    
    $('#submit_updateJMSSecurityInfo').live('click',function()
    {
        $.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'user',
			   subq : 'update_JMSSecurityInfo',
			   usr_sid : $('#usr_sid').val(),
               usr_Estimates : $('#usr_Estimates').val(),
               usr_Contracts : $('#usr_Contracts').val(),
               usr_Jobs : $('#usr_Jobs').val(),
               usr_Leads : $('#usr_Leads').val(),
               usr_Reports : $('#usr_Reports').val(),
               usr_Messages : $('#usr_Messages').val(),
               usr_System : $('#usr_System').val(),
               usr_updt_m_plev : $('#usr_updt_m_plev').val(),
               usr_updt_m_llev : $('#usr_updt_m_llev').val(),
               usr_updt_m_ulev : $('#usr_updt_m_ulev').val(),
               usr_updt_m_mlev : $('#usr_updt_m_mlev').val(),
               usr_updt_m_tlev : $('#usr_updt_m_tlev').val(),
			   optype : 'table'
			},
			success : function(data){
				//$('#panel_JMSSecurityInfo').html(data).show(500);
                $('#panel_JMSSecurityInfo').html(updateHTML).show(500);
                show_JMSSecurity();
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#panel_JMSsecurityInfo').html(textStatus).show(500);
			}
		});
    });
    
    function show_JMSFunctional()
	{
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'user',
			   subq : 'get_JMSFunctionalInfo',
			   sid : $('#UserSID').val(),
			   optype : 'table'
			},
			success : function(data){
				$('#panel_JMSFunctionalInfo').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#panel_JMSFunctionalInfo').html(textStatus).show(500);
			}
		});
		
		return true;
	}
    
    $('#submit_updateJMSFunctionalInfo').live('click',function()
    {
        $.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
                call : 'user',
                subq : 'update_JMSFunctionalInfo',
                usr_sid : $('#usr_sid').val(),
                usr_passcnt : $('#usr_passcnt').val(),
                usr_acctngrelease : $('#usr_acctngrelease').val(),
                usr_devmode : $('#usr_devmode').val(),
                usr_tester : $('#usr_tester').val(),
				usr_testerenable : $('#usr_testerenable').val(),
				usr_jobprogress : $('#usr_jobprogress').val(),
				usr_screport : $('#usr_screport').val(),
                usr_admstaff : $('#usr_admstaff').val(),
                usr_modcomm : $('#usr_modcomm').val(),
                usr_excmess : $('#usr_excmess').val(),
                usr_gmreports : $('#usr_gmreports').val(),
                usr_digstandingrpt : $('#usr_digstandingrpt').val(),
                usr_admindigreport : $('#usr_admindigreport').val(),
                usr_contactlist : $('#usr_contactlist').val(),
                usr_returntolist : $('#usr_returntolist').val(),
                usr_officelist : $('#usr_officelist').val(),
                usr_enotify : $('#usr_enotify').val(),
                usr_csrep : $('#usr_csrep').val(),
                usr_emailtemplateaccess : $('#usr_emailtemplateaccess').val(),
                usr_networkaccess : $('#usr_networkaccess').val(),
                usr_filestoreaccess : $('#usr_filestoreaccess').val(),
                usr_constructdateaccess : $('#usr_constructdateaccess').val(),
                usr_PurchaseOrder : $('#usr_PurchaseOrder').val(),
                usr_conspiperpt : $('#usr_conspiperpt').val(),
                optype : 'table'
			},
			success : function(data){
                $('#panel_JMSFunctionalInfo').html(updateHTML).show(500);
                show_JMSFunctional();
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#panel_JMSFunctionalInfo').html(textStatus).show(500);
			}
		});
    });
    
    function show_JMSProfiles()
	{
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'user',
			   subq : 'get_JMSProfilesInfo',
			   sid : $('#UserSID').val(),
			   optype : 'table'
			},
			success : function(data){
				$('#panel_JMSProfilesInfo').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#panel_JMSProfilesInfo').html(textStatus).show(500);
			}
		});
		
		return true;
	}
    
    $('#submit_insertJMSUserProfile').live('click',function()
    {
        $.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
                call : 'user',
                subq : 'insert_JMSUserProfile',
                usr_sid : $('#usr_sid').val(),
                usr_asid : $('#usr_asid').val(),
                optype : 'table'
			},
			success : function(data){
				$('#panel_JMSProfilesInfo').html(updateHTML).show();
                show_JMSProfiles();
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#panel_JMSProfilesInfo').html(textStatus).show(500);
			}
		});
    });
    
    function show_JMSSalesRep()
	{
        //alert('SalesRep info');
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'user',
			   subq : 'get_JMSSalesRepInfo',
			   sid : $('#UserSID').val(),
			   optype : 'table'
			},
			success : function(data){
				$('#panel_JMSSalesRepInfo').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#panel_JMSSalesRepInfo').html(textStatus).show(500);
			}
		});
		
		return true;
	}
    
    $('#submit_updateJMSSalesRepInfo').live('click',function()
    {
        $.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
                call : 'user',
                subq : 'update_JMSSalesRepInfo',
                usr_sid : $('#UserSID').val(),
                usr_salesrep : $('#usr_salesrep').val(),
                usr_newcommdate : $('#usr_newcommdate').val(),
                optype : 'table'
			},
			success : function(data){
                $('#panel_JMSSalesRepInfo').html(updateHTML).show();
                show_JMSSalesRep();
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#panel_JMSSalesRepInfo').html(textStatus).show(500);
			}
		});
    });
    
    function show_MASAccounting()
	{
		$('#panel_MASAccountingInfo').html(spinnerIMG).show(500);
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'user',
			   subq : 'get_MASAccountingInfo',
			   sid : $('#UserSID').val(),
			   optype : 'table'
			},
			success : function(data){
				$('#panel_MASAccountingInfo').empty().html(data).show(500);
				$('button').button();
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#panel_MASAccountingInfo').empty().html(textStatus).show(500);
			}
		});

		return true;
	}
    
    function show_QBSAccounting()
	{
        $('#panel_QBSAccountingInfo').html(spinnerIMG).show(500);
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'user',
			   subq : 'get_QBSAccountingInfo',
			   sid : $('#UserSID').val(),
			   optype : 'table'
			},
			success : function(data){
				$('#panel_QBSAccountingInfo').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#panel_QBSAccountingInfo').html(textStatus).show(500);
			}
		});
        
        $('#status_accounting_qbs').html(spinnerIMG).show(500);
        $.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'user',
			   subq : 'get_EmployeeStatus',
			   sid : $('#UserSID').val(),
			   optype : 'table'
			},
			success : function(data){
				$('#status_accounting_qbs').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#status_accounting_qbs').text(textStatus).show(500);
			}
		});
        
        $('#status_qbs_query').html(spinnerIMG).show(500);
        $.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'user',
			   subq : 'get_QueryResults_List',
			   sid : $('#UserSID').val(),
			   optype : 'table'
			},
			success : function(data){
				$('#status_qbs_query').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#status_qbs_query').text(textStatus).show(500);
			}
		});
		
		return true;
	}
    
    $('.view_QBQR_List').live('click',function()
    {
        //alert($(this).val());
        $('#status_qbs_query').html(spinnerIMG).show(500);
        $.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'user',
			   subq : 'get_QueryResults_List',
			   sid : $('#UserSID').val(),
			   optype : 'table'
			},
			success : function(data){
				$('#status_qbs_query').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#status_qbs_query').text(textStatus).show(500);
			}
		});
        
        return true;
    });
    
    $('.view_EmployeeQBQR').live('click',function()
    {
        //alert($(this).val());
        $('#status_qbs_query').html(spinnerIMG).show(500);
        $.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'user',
			   subq : 'get_QBSQueryResult',
			   sid : $('#UserSID').val(),
               qid : $(this).val(),
               qact : 'EmployeeRet',
			   optype : 'table'
			},
			success : function(data){
				$('#status_qbs_query').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#status_qbs_query').text(textStatus).show(500);
			}
		});
    });
    
    $('.remove_EmployeeQBQR').live('click',function()
    {
        //alert($(this).val());
        var agree = confirm('ATTENTION!\n\nYou are attempting to remove a Quickbooks Query Result.\n\nIf this result has not been processed, a new Query request will need to be sent to Quickbooks.\n\nClick OK to verify.\nClick CANCEL if you do not wish to make this change');
		
		if (agree)
		{
            $('#status_qbs_query').html(processIMG).show(500);
            $.ajax({
                cache:false,
                type : 'POST',
                url : ajxscript,
                dataType : 'html',
                data: {
                   call : 'user',
                   subq : 'remove_EmployeeQueryResults',
                   sid : $('#UserSID').val(),
                   qid : $(this).val(),
                   qact : 'Employee',
                   optype : 'table'
                },
                success : function(data){
                    $('#status_qbs_query').html(data).show(500);
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    $('#status_qbs_query').text(textStatus).show(500);
                }
            });
            
            show_QBSAccounting();
            return true;
        }
        else
        {
            return false;    
        }
    });
    
    $('.process_EmployeeQBQR').live('click',function()
    {
        //alert($(this).val());
        var agree = confirm('ATTENTION!\n\nYou are attempting to process a Quickbooks Query Result.\n\nThis should only be performed if the result Employee EditSequence does not match the JMS Employee EditSequence.\n\nClick OK to verify.\nClick CANCEL if you do not wish to make this change');
		
		if (agree)
		{
            $('#status_qbs_query').html(processIMG).show(500);
            alert($(this).val());
            /*
            $.ajax({
                cache:false,
                type : 'POST',
                url : ajxscript,
                dataType : 'html',
                data: {
                   call : 'user',
                   subq : 'remove_EmployeeQueryResults',
                   sid : $('#UserSID').val(),
                   qid : $(this).val(),
                   qact : 'Employee',
                   optype : 'table'
                },
                success : function(data){
                    $('#status_qbs_query').html(data).show(500);
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    $('#status_qbs_query').text(textStatus).show(500);
                }
            });
            
            show_QBSAccounting();
            */
            
            return true;
        }
        else
        {
            return false;    
        }
    });
    
    $('.view_SalesRepQBQR').live('click',function()
    {
        //alert($(this).val());
        $('#status_qbs_query').html(spinnerIMG).show(500);
        $.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'user',
			   subq : 'get_QBSQueryResult',
			   sid : $('#UserSID').val(),
               qid : $(this).val(),
               qact : 'SalesRepRet',
			   optype : 'table'
			},
			success : function(data){
				$('#status_qbs_query').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#status_qbs_query').text(textStatus).show(500);
			}
		});
    });
    
    $('.process_SalesRepQBQR').live('click',function()
    {
        //alert($(this).val());
        var agree = confirm('ATTENTION!\n\nYou are attempting to process a Quickbooks Query Result.\n\nThis should only be performed if the result SalesRep EditSequence does not match the JMS SalesRep EditSequence.\n\nClick OK to verify.\nClick CANCEL if you do not wish to make this change');
		
		if (agree)
		{
            $('#status_qbs_query').html(processIMG).show(500);
            alert($(this).val());
            /*
            $.ajax({
                cache:false,
                type : 'POST',
                url : ajxscript,
                dataType : 'html',
                data: {
                   call : 'user',
                   subq : 'remove_EmployeeQueryResults',
                   sid : $('#UserSID').val(),
                   qid : $(this).val(),
                   qact : 'Employee',
                   optype : 'table'
                },
                success : function(data){
                    $('#status_qbs_query').html(data).show(500);
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    $('#status_qbs_query').text(textStatus).show(500);
                }
            });
            
            show_QBSAccounting();
            */
            
            return true;
        }
        else
        {
            return false;    
        }
    });
    
    $('#refresh_QBEmployeeConfig').live('click',function()
    {
        show_QBSAccounting();
    });
    
    $('#update_MASAccountingInfo').live('click',function()
    {
		var umo=$('#usr_mas_office').val();
		var ump=$('#usr_mas_prid').val();
		var umd=$('#usr_mas_div').val();
		var umi=$('#usr_masid').val();
		var urd=$('#usr_rmas_div').val();
		var uri=$('#usr_rmasid').val();

		$('#panel_MASAccountingInfo').html(updateHTML).show();		
        $.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
                call : 'user',
                subq : 'update_MASAccountingInfo',
                usr_sid : $('#UserSID').val(),
                usr_mas_office : umo,
                usr_mas_prid : ump,
                usr_mas_div : umd,
                usr_masid : umi,
                usr_rmas_div : urd,
                usr_rmasid : uri,
                optype : 'table'
			},
			success : function(data){
				//alert(data);
                $('#panel_MASAccountingInfo').empty().html(data).show(500);
                //show_MASAccounting();
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#panel_MASAccountingInfo').html(textStatus).show(500);
			}
		});
    });
    
    $('#query_EmployeeQBSConfig').live('click',function()
    {
        $('#status_qbs_query').html(processIMG).show();
        $.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
                call : 'user',
                subq : 'query_EmployeeQBSConfig',
                oid : $('#UserOID').val(),
                sid : $('#UserSID').val(),
                qb_fname : $('#sys_fname_qbs').val(),
                qb_lname : $('#sys_lname_qbs').val(),
                qb_LI : $('#sys_ListID_qbs').val(),
                qb_ES : $('#sys_EditS_qbs').val(),
                optype : 'table'
			},
			success : function(data){
                $('#status_qbs_query').html(data).show();
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#status_qbs_query').html(textStatus).show(500);
			}
		});
        
        $.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
                call : 'user',
                subq : 'query_SalesRepQBSConfig',
                oid : $('#UserOID').val(),
                sid : $('#UserSID').val(),
                qb_fname : $('#sys_fname_qbs').val(),
                qb_lname : $('#sys_lname_qbs').val(),
                qb_SRLI : $('#sys_SR_ListID_qbs').val(),
                qb_SRES : $('#sys_SR_EditS_qbs').val(),
                optype : 'table'
			},
			success : function(data){
                $('#status_qbs_query').html(data).show();
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#status_qbs_query').html(textStatus).show(500);
			}
		});
    });
    
    function show_JMSAltOfficeAccess()
	{
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'user',
			   subq : 'get_JMSAltOfficeAccessInfo',
			   sid : $('#UserSID').val(),
			   optype : 'table'
			},
			success : function(data){
				$('#panel_JMSAltOfficeAccessInfo').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#panel_JMSAltOfficeAccessInfo').html(textStatus).show(500);
			}
		});
		
		return true;
	}
    
    function show_JMSUserSysInfo()
	{
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'user',
			   subq : 'get_JMSUserSysInfo',
			   sid : $('#UserSID').val(),
			   optype : 'table'
			},
			success : function(data){
				$('#panel_JMSUserSysInfo').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#panel_JMSUserSysInfo').html(textStatus).show(500);
			}
		});
		
		return true;
	}    
});