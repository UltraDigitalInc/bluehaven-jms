$(document).ready(function()
{
	var ajxscript	= 'subs/ajax_office_req.php';
	var ViewOfficeInfo_menu_tab_id	= parseInt($.cookie("ViewOfficeInfo_menu_tab")) || 0;
	var spinnerIMG	= '<img src="images/mozilla_blu.gif">';
	var updateTEXT = '<em>Updating....</em>';
	var updateHTML = '<img src="images/mozilla_blu.gif"> Updating...';
	
	$('button').button();
   
	$('#ViewOfficeInfo').tabs({
		cache:false,
		selected:ViewOfficeInfo_menu_tab_id,
		spinner:spinnerIMG,
		show:function(event,ui){
			var show_tab_id = ui.index;
			$.cookie("ViewOfficeInfo_menu_tab", show_tab_id);
			
			//alert(show_tab_id);
			
			switch(show_tab_id)
			{
				case 0:
					show_GeneralOfficeInfo();
				break;
			
				case 1:
					show_GeneralOfficeConfig();
				break;
			
				case 2:
					show_PaymentScheduleConfig();
				break;
			
				case 3:
					show_MASAccountingConfig();
				break;
			
				case 4:
					show_PricebookConfig();
				break;
			
				case 5:
					show_FeeScheduleConfig();
				break;
			
				case 6:
					show_FileStorageConfig();
				break;
			
				case 7:
					show_RoutingMatrixConfig();
				break;
			
				case 8:
					show_SalesTaxConfig();
				break;
			
				case 9:
					show_FinanceConfig();
				break;
			}
			
			show_LastOfficeUpdate()
		}
	});
	
	function show_LastOfficeUpdate()
	{
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'office',
			   subq : 'get_LastOfficeUpdate',
			   goid : $('#OffConfigOID').val(),
			   optype : 'table'
			},
			success : function(data){
				$('#LastOfficeUpdate').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#LastOfficeUpdate').html(textStatus).show(500);
			}
		});
		
		return true;
	}
	
	function show_GeneralOfficeInfo()
	{
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'office',
			   subq : 'get_GeneralOfficeInfo',
			   goid : $('#OffConfigOID').val(),
			   optype : 'table'
			},
			success : function(data){
				$('#panel_GeneralOfficeInfo').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#panel_GeneralOfficeInfo').html(textStatus).show(500);
			}
		});
		
		return true;
	}
	
	function show_GeneralOfficeConfig()
	{
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'office',
			   subq : 'get_GeneralOfficeConfig',
			   goid : $('#OffConfigOID').val(),
			   optype : 'table'
			},
			success : function(data){
			   $('#panel_GeneralOfficeConfig').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
			   $('#panel_GeneralOfficeConfig').html(textStatus).show(500);
			}
		});
		
		return true;
	}
	
	function show_PaymentScheduleConfig()
	{
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'office',
			   subq : 'get_PaymentScheduleConfig',
			   goid : $('#OffConfigOID').val(),
			   optype : 'table'
			},
			success : function(data){
			   $('#panel_PaymentScheduleConfig').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
			   $('#panel_PaymentScheduleConfig').html(textStatus).show(500);
			}
		});
		
		return true;
	}
	
	function show_FeeScheduleConfig()
	{
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'office',
			   subq : 'get_FeeScheduleConfig',
			   goid : $('#OffConfigOID').val(),
			   optype : 'table'
			},
			success : function(data){
			   $('#panel_FeeScheduleConfig').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
			   $('#panel_FeeScheduleConfig').html(textStatus).show(500);
			}
		});
		
		return true;
	}
	
	function show_MASAccountingConfig()
	{
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'office',
			   subq : 'get_MASAccountingConfig',
			   goid : $('#OffConfigOID').val(),
			   optype : 'table'
			},
			success : function(data){
			   $('#panel_MASAccountingConfig').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
			   $('#panel_MASAccountingConfig').html(textStatus).show(500);
			}
		});
		
		return true;
	}
	
	function show_QBAccountingConfig()
	{
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'office',
			   subq : 'get_QBAccountingConfig',
			   goid : $('#OffConfigOID').val(),
			   optype : 'table'
			},
			success : function(data){
				$('#panel_QBAccountingConfig').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
			   $('#panel_QBAccountingConfig').html(textStatus).show(500);
			}
		});
		
		return true;
	}
	
	function show_RoutingMatrixConfig()
	{
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'office',
			   subq : 'get_RoutingMatrixConfig',
			   goid : $('#OffConfigOID').val(),
			   optype : 'table'
			},
			success : function(data){
			   $('#panel_RoutingMatrixConfig').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
			   $('#panel_RoutingMatrixConfig').html(textStatus).show(500);
			}
		});
		
		return true;
	}
	
	function show_SalesTaxConfig()
	{
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'office',
			   subq : 'get_SalesTaxConfig',
			   goid : $('#OffConfigOID').val(),
			   optype : 'table'
			},
			success : function(data){
			   $('#panel_SalesTaxConfig').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
			   $('#panel_SalesTaxConfig').html(textStatus).show(500);
			}
		});
		
		return true;
	}
	
	function show_FinanceConfig()
	{
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'office',
			   subq : 'get_FinanceConfig',
			   goid : $('#OffConfigOID').val(),
			   optype : 'table'
			},
			success : function(data){
			   $('#panel_FinanceConfig').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
			   $('#panel_FinanceConfig').html(textStatus).show(500);
			}
		});
		
		return true;
	}
	
	function show_FileStorageConfig()
	{
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'office',
			   subq : 'get_FileStorageConfig',
			   goid : $('#OffConfigOID').val(),
			   optype : 'table'
			},
			success : function(data){
			   $('#panel_FileStorageConfig').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
			   $('#panel_FileStorageConfig').html(textStatus).show(500);
			}
		});
		
		return true;
	}
	
	function show_PricebookConfig()
	{
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'office',
			   subq : 'get_PricebookConfig',
			   goid : $('#OffConfigOID').val(),
			   optype : 'table'
			},
			success : function(data){
			   $('#panel_PricebookConfig').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
			   $('#panel_PricebookConfig').html(textStatus).show(500);
			}
		});
		
		return true;
	}
	
	$('#addNewTaxPermit').live('click',function(event){
		event.preventDefault();
		var st_oid=$('#st_oid').val();
		var city=$('#addtpcity').val();
		var permit=$('#addtppermit').val();
		var wryder=$('#addtpwryder').val();
		var taxrate=$('#addtptaxrate').val();
		
		if (city.length > 0 && taxrate.length > 0)
		{
			//alert(st_oid);
			$.ajax({
				cache:false,
				type : 'POST',
				url : ajxscript,
				dataType : 'html',
				data: {
				   call : 'office',
				   subq : 'add_NewTaxPermit',
				   goid : st_oid,
				   gcity : city,
				   gpermit : permit,
				   gwryder : wryder,
				   gtaxrate : taxrate,
				   optype : 'table'
				},
				success : function(data){
					show_SalesTaxConfig();
				},
				error : function(XMLHttpRequest, textStatus, errorThrown) {
				   alert(textStatus);
				}
			});
			
		}
		else
		{
			alert('Missing City or Tax Rate');
		}
	});
	
	$('.upd_thisTaxRate').live('click',function(event){
		event.preventDefault();
		var oid=$('#st_oid').val();
		var gid=parseInt($(this).parent().children('.staxupdID').val());
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'office',
			   subq : 'upd_thisTaxRate',
			   goid : oid,
			   gstid : gid,
			   optype : 'table'
			},
			success : function(data){
				show_SalesTaxConfig();
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
			   alert(textStatus);
			}
		});
	});
	
	$('#submit_UpdateGeneralOfficeInfo').live('click',function() {
		//$('#panel_GeneralOfficeInfo').html(updateTEXT).show(500);
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'office',
			   subq : 'update_GeneralOfficeInfo',
			   gi_oid : $('#gi_oid').val(),
			   gi_active : $('#gi_active').val(),
			   gi_name : $('#gi_name').val(),
			   gi_label_masoff_code : $('#gi_label_masoff_code').val(),
			   //gi_mascode : $('#gi_mascode').val(),
			   gi_addr1 : $('#gi_addr1').val(),
			   gi_addr2 : $('#gi_addr2').val(),
			   gi_city : $('#gi_city').val(),
			   gi_state : $('#gi_state').val(),
			   gi_adminonly : $('#gi_adminonly').val(),
			   gi_finan_off : $('#gi_finan_off').val(),
			   gi_grouping : $('#gi_grouping').val(),
			   gi_otype : $('#gi_otype').val(),
			   gi_otype_code : $('#gi_otype_code').val(),
			   gi_conlicense : $('#gi_conlicense').val(),
			   gi_phone : $('#gi_phone').val(),
			   gi_fax : $('#gi_fax').val(),
			   gi_gm : $('#gi_gm').val(),
			   gi_sm : $('#gi_sm').val(),
			   gi_am : $('#gi_am').val(),
			   gi_processor : $('#gi_processor').val(),
			   gi_leadforward : $('#gi_leadforward').val(),
			   gi_csrep: $('#gi_csrep').val()
			},
			success : function(data){
				$('#panel_GeneralOfficeInfo').html(show_GeneralOfficeInfo()).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
			   $('#panel_GeneralOfficeInfo').html(textStatus).show(500);
			}
		});
		
		show_LastOfficeUpdate();
	});
   
	$('#submit_UpdateGeneralOfficeConfig').live('click',function() {
		//alert('Submitted');
		//$('#panel_GeneralOfficeConfig').html(updateTEXT).show(500);
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'office',
			   subq : 'update_GeneralOfficeConfig',
			   gc_oid : $('#gc_oid').val(),
			   gc_timeshift: $('#gc_timeshift').val(),
			   gc_enest: $('#gc_enest').val(),
			   gc_encon: $('#gc_encon').val(),
			   gc_enjob: $('#gc_enjob').val(),
			   gc_endigreport: $('#gc_endigreport').val(),
			   gc_enexp: $('#gc_enexp').val(),
			   gc_encost: $('#gc_encost').val(),
			   gc_manphsadj: $('#gc_manphsadj').val(),
			   gc_enmas: $('#gc_enmas').val(),
			   gc_masimport: $('#gc_masimport').val(),
			   gc_leadmail: $('#gc_leadmail').val(),
			   gc_ldexport: $('#gc_ldexport').val(),
			   gc_gmrjoin: $('#gc_gmrjoin').val(),
			   gc_logging: $('#gc_logging').val(),
			   gc_intro_etid: $('#gc_intro_etid').val(),
			   gc_PurchaseOrder: $('#gc_PurchaseOrder').val(),
			   gc_accountingsystem: $('#gc_accountingsystem').val(),
			   gc_constructiondates: $('#gc_constructiondates').val()
			},
			success : function(data){
				$('#panel_GeneralOfficeConfig').html(updateHTML).show();
				show_GeneralOfficeConfig();
				//$('#panel_GeneralOfficeConfig').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
			   $('#panel_GeneralOfficeConfig').html(textStatus).show(500);
			}
		});
		
		show_LastOfficeUpdate();
	});
	
	$('#submit_UpdatePricebookConfig').live('click',function() {
		//alert('Submitted');
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'office',
			   subq : 'update_PricebookConfig',
			   pb_oid : $('#pb_oid').val(),
			   pb_com_rate : $('#pb_com_rate').val(),
			   pb_bullet_rate: $('#pb_bullet_rate').val(),
			   pb_bullet_cnt: $('#pb_bullet_cnt').val(),
			   pb_over_split: $('#pb_over_split').val(),
			   pb_tgp: $('#pb_tgp').val(),
			   pb_vgp: $('#pb_vgp').val(),
			   pb_stax: $('#pb_stax').val(),
			   pb_deckinc: $('#pb_deckinc').val(),
			   pb_manphsadj: $('#pb_manphsadj').val(),
			   pb_all_code: $('#pb_all_code').val()
			},
			success : function(data){
				$('#panel_PricebookConfig').html(updateTEXT).show(500);
				$('#panel_PricebookConfig').html(show_PricebookConfig()).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
			   $('#panel_PricebookConfig').html(textStatus).show(500);
			}
		});
		
		show_LastOfficeUpdate();
	});
	
	$('#submit_UpdateFileStorageConfig').live('click',function() {
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'office',
			   subq : 'update_FileStorageConfig',
			   fs_oid : $('#fs_oid').val(),
			   fs_fscustomer: $('#fs_fscustomer').val(),
			   fs_fsshared: $('#fs_fsshared').val(),
			   fs_fsoffice: $('#fs_fsoffice').val(),
			   fs_fslimit: $('#fs_fslimit').val()
			},
			success : function(data){
				$('#panel_FileStorageConfig').html(updateTEXT).show(500);
				$('#panel_FileStorageConfig').html(show_FileStorageConfig()).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
			   $('#panel_FileStorageConfig').html(textStatus).show(500);
			}
		});
		
		show_LastOfficeUpdate();
	});
	
	$('#submit_UpdateFeeScheduleConfig').live('click',function() {
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'office',
			   subq : 'update_FeeScheduleConfig',
			   es_oid : $('#es_oid').val(),
			   es_consfee : $('#es_consfee').val(),
			   es_acctfee : $('#es_acctfee').val(),
			   es_pacctfee : $('#es_pacctfee').val()
			},
			success : function(data){
				$('#panel_FeeScheduleConfig').html(updateTEXT).show(500);
				$('#panel_FeeScheduleConfig').html(show_FeeScheduleConfig()).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
			   $('#panel_FeeScheduleConfig').html(textStatus).show(500);
			}
		});
		
		show_LastOfficeUpdate();
	});
	
	$('#submit_UpdateAccountingTypeConfig').live('click',function() {
		if ($('#ac_accountingsystem').val()!=$('#ac_curr').val())
		{
			var agree = confirm('ATTENTION!\n\nYou are attempting to change the current Accounting Configuration.\n\nClick OK to Continue.\nClick CANCEL to Stop');
			
			if (agree)
			{
				$.ajax({
					cache:false,
					type : 'POST',
					url : ajxscript,
					dataType : 'html',
					data: {
					   call : 'office',
					   subq : 'update_AccountingTypeConfig',
					   ac_oid : $('#ac_oid').val(),
					   ac_accountingsystem : $('#ac_accountingsystem').val()
					},
					success : function(data){
						$('#panel_MASAccountingConfig').html(updateHTML).show();
						show_LastOfficeUpdate();
						//$('#panel_AccountingConfig').html(show_AccountingConfig()).show(500);
						//$('#panel_AccountingConfig').html(data).show(500);
					},
					error : function(XMLHttpRequest, textStatus, errorThrown) {
					   $('#panel_AccountingConfig').html(textStatus).show(500);
					}
				});
				
				//show_LastOfficeUpdate();
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	});
	
	$('#submit_UpdateAccountingXMLConfig').live('click',function() {
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'office',
			   subq : 'update_AccountingXMLConfig',
			   xl_oid : $('#xl_oid').val(),
			   xl_enexp : $('#xl_enexp').val(),
			   xl_enmas : $('#xl_enmas').val(),
			   xl_masimport : $('#xl_masimport').val(),
			   xl_exportserver : $('#xl_exportserver').val(),
			   xl_exportlogin : $('#xl_exportlogin').val(),
			   xl_exportpass : $('#xl_exportpass').val(),
			   xl_exportcatalog : $('#xl_exportcatalog').val()
			},
			success : function(data){
				$('#panel_MASAccountingConfig').html(updateHTML).show(500);
				show_MASAccountingConfig();
				//$('#panel_QBAccountingConfig').html(show_AccountingConfig()).show(500);
				
				//$('#panel_AccountingConfig').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
			   $('#panel_AccountingConfig').html(textStatus).show(500);
			}
		});
		
		show_LastOfficeUpdate();
	});
	
	$('#submit_UpdateAccountingQBXMLConfig').live('click',function() {
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
				call : 'office',
				subq : 'update_AccountingQBXMLConfig',
				qb_oid : $('#qb_oid').val(),
				qb_reccnt : $('#qb_reccnt').val(),
				qb_AppDescription : $('#qb_AppDescription').val(),
				qb_AppDisplayName : $('#qb_AppDisplayName').val(),
				qb_AppID : $('#qb_AppID').val(),
				qb_AppName : $('#qb_AppName').val(),
				qb_AppSupport : $('#qb_AppSupport').val(),
				qb_AppUniqueName : $('#qb_AppUniqueName').val(),
				qb_AppURL : $('#qb_AppURL').val(),
				qb_AuthFlags : $('#qb_AuthFlags').val(),
				qb_FileID : $('#qb_FileID').val(),
				qb_IsReadOnly : $('#qb_IsReadOnly').val(),
				qb_Notify : $('#qb_Notify').val(),
				qb_OwnerID : $('#qb_OwnerID').val(),
				qb_PersonalDataPref : $('#qb_PersonalDataPref').val(),
				qb_QBType : $('#qb_QBType').val(),
				qb_Scheduler : $('#qb_Scheduler').val(),
				qb_Style : $('#qb_Style').val(),
				qb_UnattendedModePref : $('#qb_UnattendedModePref').val(),
				qb_UserName : $('#qb_UserName').val(),
				qb_SoapHost : $('#qb_SoapHost').val(),
				qb_SoapDB : $('#qb_SoapDB').val(),
				qb_SoapUser : $('#qb_SoapUser').val(),
				qb_SoapPass : $('#qb_SoapPass').val()
			},
			success : function(data){
				$('#panel_QBAccountingConfig').html(updateHTML).show();
				show_QBAccountingConfig();
				//$('#panel_QBAccountingConfig').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#panel_QBAccountingConfig').html(textStatus).show(500);
			}
		});
		
		show_LastOfficeUpdate();
	});
	
	$('#submit_UpdateRoutingMatrixConfig').live('click',function() {
		
		if ($('#rm_mties').val() > 0)
		{
			var agree = confirm('CAUTION!!\n\nModify Routing Matrix fields with extreme care.\nChanges can have unwanted effects on the Routing Systems\n\nClick OK to Continue.\nClick CANCEL to Stop');
		}
		else
		{
			var agree = true;
		}

		if (agree)
		{
			$.ajax({
				cache:false,
				type : 'POST',
				url : ajxscript,
				dataType : 'html',
				data: {
					call : 'office',
					subq : 'update_RoutingMatrixConfig',
					rm_oid : $('#rm_oid').val(),
					rm_nozip : $('#rm_nozip').val(),
					rm_noringto : $('#rm_noringto').val()
				},
				success : function(data){
					$('#panel_RoutingMatrixConfig').html(updateTEXT).show(500);
					$('#panel_RoutingMatrixConfig').html(show_RoutingMatrixConfig()).show(500);
					//$('#panel_RoutingMatrixConfig').html(data).show(500);
				},
				error : function(XMLHttpRequest, textStatus, errorThrown) {
					$('#panel_RoutingMatrixConfig').html(textStatus).show(500);
				}
			});
		
			show_LastOfficeUpdate();
			return true;
		}
		else
		{
			return false;
		}
	});
	
	$('#submit_UpdateSalesTaxBaseConfig').live('click',function() {
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'office',
			   subq : 'update_SalesTaxBaseConfig',
			   st_oid : $('#st_oid').val(),
			   st_stax : $('#st_stax').val()
			},
			success : function(data){
				$('#panel_SalesTaxConfig').html(updateTEXT).show(500);
				$('#panel_SalesTaxConfig').html(show_SalesTaxConfig()).show(500);
				//$('#panel_AccountingConfig').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
			   $('#panel_SalesTaxConfig').html(textStatus).show(500);
			}
		});
		
		show_LastOfficeUpdate();
	});
	
	$('#submit_UpdatePaymentScheduleConfig').live('click',function() {
		//alert(get_PaySchedulePhsCode());
		//alert(get_PaySchedulePhsAmt());
		
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
			   call : 'office',
			   subq : 'update_PaymentScheduleConfig',
			   ps_oid : $('#ps_oid').val(),
			   ps_phsCode : get_PaySchedulePhsCode(),
			   ps_phsAmt : get_PaySchedulePhsAmt()
			},
			success : function(data){
				$('#panel_PaymentScheduleConfig').html(updateTEXT).show(500);
				$('#panel_PaymentScheduleConfig').html(show_PaymentScheduleConfig()).show(500);
				//$('#panel_PaymentScheduleConfig').html(data).show(500);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
			   $('#panel_PaymentScheduleConfig').html(textStatus).show(500);
			}
		});
		
		show_LastOfficeUpdate();
	});
	
	$('#add_PhaseCode').live('click',function() {
		/*
		if ($('div#PaySchedContainer').find('text',$('#sel_PhaseCode').val()))
		{
			alert($('#sel_PhaseCode').val() + ' Found!');
		}
		*/
		
		$('#PaySchedContainer').append('<p><span class="phsCode">' + $('#sel_PhaseCode').val() + '</span><input class="phsAmt" type="text" value="0.0" align=\"center\" size="5"><a id="del_PhaseCode" href="#"> <img src=\"images/delete.png\" title=\"Delete Phase\"></a></p>');
	});
	
	$('#del_PhaseCode').live('click',function() {	
		$(this).parent().remove();
	});
	
	function get_PaySchedulePhsCode()
	{
		var HTMLout=''
		
		$('.phsCode').each(function(index){
			if (index===0)
			{
				HTMLout += $(this).html();	
			}
			else
			{
				HTMLout += ',' + $(this).html();
			}
		});
		
		//alert(HTMLout);
		return HTMLout;
	}
	
	function get_PaySchedulePhsAmt()
	{
		var HTMLout=''
		
		$('.phsAmt').each(function(index){
			if (index===0)
			{
				HTMLout += $(this).val();
			}
			else
			{
				HTMLout += ',' + $(this).val();
			}
		});
		
		//alert(HTMLout);
		return HTMLout;
	}
	
	$('#submit_DeletePaymentScheduleConfig').live('click',function() {
		
		var agree = confirm('ATTENTION!\n\nYou are attempting to Delete the Current Payment Schedule.\n\nClick OK to Continue.\nClick CANCEL to Stop');
		
		if (agree)
		{
			$.ajax({
				cache:false,
				type : 'POST',
				url : ajxscript,
				dataType : 'html',
				data: {
				   call : 'office',
				   subq : 'update_PaymentScheduleConfig',
				   ps_oid : $('#dps_oid').val(),
				   ps_phsCode : '0',
				   ps_phsAmt : '0'
				},
				success : function(data){
					$('#panel_PaymentScheduleConfig').html(updateTEXT).show(500);
					$('#panel_PaymentScheduleConfig').html(show_PaymentScheduleConfig()).show(500);
					//$('#panel_PaymentScheduleConfig').html(data).show(500);
				},
				error : function(XMLHttpRequest, textStatus, errorThrown) {
				   $('#panel_PaymentScheduleConfig').html(textStatus).show(500);
				}
			});
			
			show_LastOfficeUpdate();
			return true;
		}
		else
		{
			return false;
		}
	});
});