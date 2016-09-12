$(document).ready(function() {
	var syscid=parseInt($('#sysCID').text());
	var fin	=$('#finalEl');
	var ltl	=$('#tblLeadWrap');
	var lcl	=$('#LeadCommentList');
	var cex=false;
	
	if (fin.length) {
		ltl.show(800);
	}
	
	if (lcl.length) {
		getLeadCommentList(syscid,lcl);
	}
	
	$('#refreshLeadComments').live('click',function(e) {
	   getLeadCommentList(syscid,lcl);
	});
	
	$('#saveLeadComment').live('click',function(e) {
	   saveLeadComment(syscid,lcl);
	});
	
	$('.setpointer').live('hover',function(e) {
		$(this).css('cursor','pointer');
	},function() {
		$(this).css('cursor','auto');
		return false;
	});
	
	$('#expandLeadComments').live('click',function(e) {
		if (!cex) {
			cex=true;
			$('span.texpandtext').hide();
			$('span.thiddentext').show();
		}
		else {
			cex=false;
			$('span.texpandtext').show();
			$('span.thiddentext').hide();
		}
	});
	
	$('#empreviewNEW').live('click',function(e) {
		e.preventDefault();
		var etid=$('#etid').val();
		
		if (!isNaN(etid) && etid!=0) {
		   displayEmailSendDialog(syscid,etid);
		}
		else {
		   alert('Select an Email Template');
		}
	});
	
	$('#updateLeadOwner').change(function(e){
		e.preventDefault();
		var srep=$(this).val();		
		updateLeadOwner(syscid,srep);
	});
	
	$('#updateLeadStatus').change(function(e){
		e.preventDefault();
		var cstat=$(this).val();
		updateLeadStatus(syscid,cstat);
	});
	
	$('.privacyOpt').change(function(e) {
		e.preventDefault();
		var n = $(this).is(':checked');
		var opt=(n)?1:0;

		$.ajax({
			cache:false,
			type : 'POST',
			url : 'subs/ajax_lead_view_req.php',
			dataType : 'json',
			data: {
				call : 'setPrivacy',
				cid : syscid,
				optin : opt,
				optype : 'json'
			},
			success : function(data){
				$('#savePrivacyResult').empty().show().html('<img src="images/action_check.gif" title="Saved">').delay(800).hide(800);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
			   alert(textStatus);
			}
		});
	});
	
	$('.saveLeadFormData').live('click',function(e){
		e.preventDefault();
		var s=$(this).parent().children('.saveResult');
		var r=$(this).closest('.outerrnd').children('.inputContainer');
		
		if (r!=null && syscid!=0) {
			var i=r.find(':input');
			var n=r.attr('id');
			var dataObj={};
			dataObj['call']=n;
			dataObj['optype']='json';
			dataObj['cid']=syscid;
			
			$.each(i,function(k,v){
				dataObj[v.name]=v.value;
			});
			
			$.ajax({
				cache:false,
				type : 'POST',
				url : 'subs/ajax_lead_view_req.php',
				dataType : 'json',
				data: dataObj,
				success : function(data){
					if (!data.error) {
						s.empty().show().html('<img src="images/action_check.gif" title="Saved">').delay(800).hide(800);
					}
					else {
						s.empty().show().html('<img src="images/action_delete.gif" title="Not Saved:'+data.result+'">');
					}
				},
				error : function(XMLHttpRequest, textStatus, errorThrown) {
				   alert(textStatus);
				}
			});
		}
	});
	
	$('#showApptDialog').live('click',function(e){
		var el=$(this).parent().parent().parent().parent();
		displayApptAdjustDialog(syscid,el);
	});
	
	$('#showCallbackDialog').live('click',function(e){
		var el=$(this).parent().parent().parent().parent();
		displayCallbackAdjustDialog(syscid,el);
	});
	
	$('#removeCallbackDate').live('click',function(e){
		e.preventDefault();
		var conf=confirm('You are about to Delete this Callback Date\n\nClick OK to continue.')
		
		if (conf) {
			var el=$(this).parent().parent().parent().parent();
			removeCallbackDate(syscid,el);
		}
	});
	
	$('#removeApptDate').live('click',function(e){
		e.preventDefault();
		var conf=confirm('You are about to Delete this Appointment\n\nClick OK to continue.')
		
		if (conf) {
			var el=$(this).parent().parent().parent().parent();
			removeApptDate(syscid,el);
		}
	});
	
	$('#openchangeOfficeDialog').live('click',function(e){
		//alert('test');
		displaychangeOfficeDialog(syscid,$(this));
	});
	
	$('.btnCancelApptAdjust').live('click',function(e){
		e.preventDefault();
		closeApptDatePick();
	});
	
	$('.btnCancelCallbackAdjust').live('click',function(e) {
		e.preventDefault();
		closeCallbackDatePick();
	});
	
	$('#frmadjAppt').live('submit',function(e){
		e.preventDefault();
		var appt_cid=parseInt($('#apptcid').val());
		var appt_mo=parseInt($('#apptmo').val());
		var appt_da=parseInt($('#apptda').val());
		var appt_yr=parseInt($('#apptyr').val());
		var appt_hr=parseInt($('#appthr').val());
		var appt_mn=parseInt($('#apptmn').val());
		var appt_pa=parseInt($('#apptpa').val());
		
		if (!isNaN(appt_cid) && (!isNaN(appt_mo) && appt_mo!=0)){
		
			$.ajax({
				cache:false,
				type : 'POST',
				url : 'subs/ajax_lead_view_req.php',
				dataType : 'json',
				data: {
					call : 'setAppt',
					appt_cid : appt_cid,
					appt_mo : appt_mo,
					appt_da : appt_da,
					appt_yr : appt_yr,
					appt_hr : appt_hr,
					appt_mn : appt_mn,
					appt_pa : appt_pa,
					optype : 'json'
				},
				success : function(data){
					closeApptDatePick();
					$('#AppointmentDate').html(data.date);
					$('#AppointmentTime').html(data.time);
					$('#AppointmentContainer').removeClass('outerrnd').removeClass('outerrnd_ltgrn').addClass(data.lclass);
					getApptsJSON();
				},
				error : function(XMLHttpRequest, textStatus, errorThrown) {
				   alert(textStatus);
				}
			});
		}
	});
	
	$('#frmadjCallback').live('submit',function(e){
		e.preventDefault();
		var hold_cid=parseInt($('#holdcid').val());
		var hold_mo=parseInt($('#holdmo').val());
		var hold_da=parseInt($('#holdda').val());
		var hold_yr=parseInt($('#holdyr').val());
		
		if (!isNaN(hold_cid) && (!isNaN(hold_mo) && hold_mo!=0)) {
		
			$.ajax({
				cache:false,
				type : 'POST',
				url : 'subs/ajax_lead_view_req.php',
				dataType : 'json',
				data: {
					call : 'setCallback',
					hold_cid : hold_cid,
					hold_mo : hold_mo,
					hold_da : hold_da,
					hold_yr : hold_yr,
					optype : 'json'
				},
				success : function(data){
					closeCallbackDatePick();
					$('#CallbackDate').html(data.date);
					$('#CallbackContainer').removeClass('outerrnd').removeClass('outerrnd_mgnta').addClass(data.lclass);
				},
				error : function(XMLHttpRequest, textStatus, errorThrown) {
				   alert(textStatus);
				}
			});
		}
	});
	
	$('#processEmailTemplate').live('click',function(e){
		e.preventDefault();
		var etid=$('#tmpetid').val();
		
		var nerr=false;
      
		if ($('#bmeEFile').length > 0) {
		   nerr=($('#bmeEFile').val()==0)?true:false;
		}
		
		if (!nerr) {
		   processEmailTemplate(syscid,etid,lcl);
		}
		else {
		   alert('You did not select a file.');
		}		
		//processEmailTemplate(syscid,etid,lcl);
	});
	
	$('#closeEmailSendDialog').live('click',function(event){
		closeEmailSendDialog();
	});
	
	$(".cfiles_attached").live('click',function(event) {
	  var chid=$(this).attr('id');
	  var tchid=chid.split('_');
	  var nchid=parseInt(tchid[1]);
	  
	  if (!isNaN(nchid)) {
		//alert(nchid);
		 $('body').append('<div id="filedlgdisp" style="display:none"></div>');		 
		 var dialog = $('#filedlgdisp').dialog({
			open: getFileInfo(nchid),
			autoOpen: false,
			resizable: false,
			modal: true,
			position : {
			   my: "left top",
			   at: "right bottom",
			   of: event,
			   offset: "2 2"
			},
			width: 200,
			height: 150,
			buttons: {
			   Close: function() {
				  $(this).dialog('close').remove();
			   }
			}
		 }).dialog("open");
	
		 $(".ui-dialog-titlebar").hide();
	  }
	});
	
	$('#closeFileDialog').live('click',function(){
	  closeLFilesDialog();
	});
	
	$('.cfileDL').live('click',function(){
	  closeLFilesDialog();
	});
});

function removeCallbackDate(hold_cid,el) {
	if (!isNaN(hold_cid)) {
		$.ajax({
			cache:false,
			type : 'POST',
			url : 'subs/ajax_lead_view_req.php',
			dataType : 'json',
			data: {
				call : 'removeCallback',
				hold_cid : hold_cid,
				optype : 'json'
			},
			success : function(data){
				$('#CallbackDate').html(data.date);
				$('#CallbackContainer').removeClass('outerrnd').removeClass('outerrnd_mgnta').addClass('outerrnd');
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
			   alert(textStatus);
			}
		});
	}
}

function removeApptDate(appt_cid,el) {
	if (!isNaN(appt_cid)) {
		$.ajax({
			cache:false,
			type : 'POST',
			url : 'subs/ajax_lead_view_req.php',
			dataType : 'json',
			data: {
				call : 'removeApptmnt',
				appt_cid : appt_cid,
				optype : 'json'
			},
			success : function(data){
				$('#AppointmentDate').html(data.date);
				$('#AppointmentTime').html(data.time);
				$('#AppointmentContainer').removeClass('outerrnd').removeClass('outerrnd_ltgrn').addClass('outerrnd');
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
			   alert(textStatus);
			}
		});
	}
}

function getFileInfo(nchid) {
   var cid=parseInt($('#sysCID').html());

   $.get('subs/ajax_leads_req.php?call=leads&subq=get_FileList&cid='+cid+'&chid='+nchid, function(data){
	  $('#filedlgdisp').empty().append(data);
   });
}

function closeLFilesDialog() {
   $('#filedlgdisp').dialog("close").remove();
}

function displaychangeOfficeDialog(cid,el) {
	$('body').append('<div id="changeOfficeDialog"></div>');
	
	var dialog = $("#changeOfficeDialog").dialog({
		dialogClass: 'noTitleDialog',
		modal: true,
		width: 250,
		height: 150,
		open: changeOfficeForm(cid,$("#changeOfficeDialog")),
		close : closechangeOfficeDialog(),
		position: {
			my: 'middle top',
			at: 'middle bottom',
			of: el
		},
		buttons: {
            'Submit' : function() {
				//alert(cid);
				processChangeOffice(cid,$("#changeOfficeDialog"));
            },
            'Cancel' : function() {
               closechangeOfficeDialog();
            }
        }
	}).dialog("open");
   
   //$("#changeOfficeDialog").html(changeOfficeForm(cid));
   return false;
}

function closechangeOfficeDialog() {
	$("#changeOfficeDialog").empty().dialog("close").remove();
}

function parseEachRow(data) {
    var out='';
    $.each(data,function(k,v){
        out+=k+':'+v+'\n';
    });
    return out;
}

function changeOfficeForm(cid,el) {
	$.ajax({
		cache:false,
		type : 'GET',
		url : 'subs/ajax_lead_view_req.php',
		dataType : 'html',
		data: {
			call : 'getChangeOfficeForm',
			cid : cid,
			optype : 'table'
		},
		success : function(data){
			el.html(data);
		},
		error : function(XMLHttpRequest, textStatus, errorThrown) {
		   alert(textStatus);
		}
	});
	return true;
}

function processChangeOffice(cid,el) {
	var noid=parseInt($('#tnoid').val());
	
	if (!isNaN(noid) && noid!=0) {
		$.ajax({
			cache:false,
			type : 'GET',
			url : 'subs/ajax_lead_view_req.php',
			dataType : 'json',
			data: {
				call : 'saveChangeOfficeForm',
				cid : cid,
				noid : noid,
				optype : 'json'
			},
			success : function(data){
				if (!data.error) {
					el.dialog('option','buttons',{});
					el.empty().html(data.result);
					$('button').button();
					$('#returntosearch').submit();
				}
				else {
					el.empty().text(data.result);
					el.dialog('option','buttons',{'Cancel' : function() {closechangeOfficeDialog();}});
				}
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
			   alert(textStatus);
			}
		});
	}
	else {
		alert('Invalid Request');
	}
}

function updateLeadOwner(cid,srep) {
	if (cid!=0)	{
		$.ajax({
			cache:false,
			type : 'POST',
			url : 'subs/ajax_lead_view_req.php',
			dataType : 'json',
			data: {
				call : 'updateLeadOwner',
				cid : cid,
				srep : srep,
				optype : 'json'
			},
			success : function(data){
				if (!data.error) {
					$('#updateOwnerResult').empty().show().html('<img src="images/action_check.gif" title="Saved">').delay(800).hide(800);
				}
				else {
					$('#updateOwnerResult').empty().show().html('<img src="images/action_delete.gif" title="Not Saved:'+data.result+'">');
				}
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
			   alert(textStatus);
			}
		});
	}
}

function updateLeadOffice(cid,noff) {
	//alert(inp_name+':'+cid+':'+inp_val);
	if (cid!=0)	{
		$.ajax({
			cache:false,
			type : 'POST',
			url : 'subs/ajax_lead_view_req.php',
			dataType : 'json',
			data: {
				call : 'updateLeadOffice',
				cid : cid,
				noff : noff,
				optype : 'json'
			},
			success : function(data){
				if (!data.error) {
					$('#updateOfficeResult').empty().show().html('<img src="images/action_check.gif" title="Saved">').delay(800).hide(800);
				}
				else {
					$('#updateOfficeResult').empty().show().html('<img src="images/action_delete.gif" title="Not Saved:'+data.result+'">');
				}
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
			   alert(textStatus);
			}
		});
	}
}

function updateLeadStatus(cid,cstat) {
	if (cid!=0)	{
		$.ajax({
			cache:false,
			type : 'POST',
			url : 'subs/ajax_lead_view_req.php',
			dataType : 'json',
			data: {
				call : 'updateLeadStatus',
				cid : cid,
				cstat: cstat,
				optype : 'json'
			},
			success : function(data){
				if (!data.error) {
					$('#updateStatusResult').empty().show().html('<img src="images/action_check.gif" title="Saved">').delay(800).hide(800);
				}
				else {
					$('#updateStatusResult').empty().show().html('<img src="images/action_delete.gif" title="Not Saved:'+data.result+'">');
				}
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
			   alert(textStatus);
			}
		});
	}
}

function updateLeadSrcRes(cid) {
	if (cid!=0)	{
		$.ajax({
			cache:false,
			type : 'POST',
			url : 'subs/ajax_lead_view_req.php',
			dataType : 'json',
			data: {
				call : 'updateLeadSrcRes',
				cid : cid,
				source: $('#ldsource').val(),
				stage: $('#ldstage').val(),
				optype : 'json'
			},
			success : function(data){
				if (!data.error) {
					$('#ldsource').val(data.source);
					$('#ldstage').val(data.stage);
					$('#updateStageResult').empty().show().html('<img src="images/action_check.gif" title="Saved">').delay(800).hide(800);
				}
				else {
					$('#updateStageResult').empty().show().html('<img src="images/action_delete.gif" title="Not Saved:'+data.result+'">');
				}
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
			   alert(textStatus);
			}
		});
	}
}

function getLeadCommentList(cid,el)	{
	if (cid!=0)	{
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
				el.html(data).show();
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
			   el.html(textStatus).show();
			}
		});
	}
}

function displayApptAdjustDialog(cid,el) { 
	$("#jms_apptadjust_dialog").empty().dialog("close").remove();
	$('body').append('<div id="jms_apptadjust_dialog" style="display:none"></div>');
	
	var dialog = $("#jms_apptadjust_dialog").dialog({
	   dialogClass: 'noTitleDialog',
	   title : 'Adjust Appointment',
	   open : openApptDatePick(cid,el),
	   close : closeApptDatePick(),
	   autoOpen: false,
	   resizable: false,
	   modal: true,
	   width: 500,
	   height: 250,
	   position: {
		   my: 'middle top',
		   at: 'middle bottom',
		   of: el
	   }
	}).dialog("open");
	
	return false;
}

function openApptDatePick(cid,el) {
	$.getJSON('subs/ajax_leads_req.php?call=leads&subq=getAppt&optype=json&cid='+cid, function(result){
		$("#jms_apptadjust_dialog").html(AppointForm(cid,result));
		$('button').button();
	});
}

function getApptsJSON() {
	$.getJSON('subs/ajax_leads_req.php?call=leads&subq=get_AP_list_JSON&optype=json', function(result){
		cappts=result;
	});
}

function closeApptDatePick() {
	$("#jms_apptadjust_dialog").empty().dialog("close").remove();
}

function AppointForm(cid,data) {	
	var n = new Date();
	var cyr=n.getFullYear();
	var fmo=12;
	var fda=31;
	var fyr=new Date().getFullYear();
	var fhr=12;
	var fmn=60;
	
	out='';
	out+='<form id="frmadjAppt">';
	out+='<input type="hidden" id="apptcid" value="'+cid+'">';
	out+='<table><tr><td>Month</td><td>Day</td><td>Year</td><td>Hour</td><td>Min</td><td>AM/PM</td><td></td></tr><tr>';
	out+='<td><select id="apptmo">';
	
	for (m=0;m<=fmo;m++) {
		out+=(data.mo==m)?'<option value="'+m+'" SELECTED>'+m+'</option>':'<option value="'+m+'">'+m+'</option>';
	}
	
	out+='</select></td>';
	out+='<td><select id="apptda">';
	
	for (d=0;d<=fda;d++) {
		out+=(data.da==d)?'<option value="'+d+'" SELECTED>'+d+'</option>':'<option value="'+d+'">'+d+'</option>';
	}
	
	out+='</select></td>';
	out+='<td><select id="apptyr">';
	
	for (y=(fyr-2);y<=(fyr+1);y++) {
		
		out+=(data.yr==y || cyr==y)?'<option value="'+y+'" SELECTED>'+y+'</option>':'<option value="'+y+'">'+y+'</option>';
	}
	
	out+='</select></td>';
	out+='<td><select id="appthr">';
	
	for (h=0;h<=fhr;h++) {
		
		out+=(data.hr==h)?'<option value="'+h+'" SELECTED>'+h+'</option>':'<option value="'+h+'">'+h+'</option>';
	}
	
	out+='</select></td>';
	out+='<td><select id="apptmn">';
	
	for (i=0;i<=fmn;i++) {
		
		out+=(data.mn==i)?'<option value="'+i+'" SELECTED>'+i+'</option>':'<option value="'+i+'">'+i+'</option>';
	}
	
	out+='</select></td>';
	out+='<td><select id="apptpa">';
	out+=(data.pa==1)?'<option value="1" SELECTED>AM</option><option value="2">PM</option>':'<option value="1">AM</option><option value="2" SELECTED>PM</option>';
	out+='</select></td>';
	out+='<td><button>Update</button></td><td><button class="btnCancelApptAdjust">Cancel</button></td>';
	out+='</tr></table></form>';
	
	//$.getJSON('subs/ajax_leads_req.php?call=leads&subq=get_AP_list_JSON&optype=json', function(result){
		//var capptsx=result;
		//console.log(typeof(capptsx));
		/*
		out+='<p><table width="425px">';
		out+='<tr><td colspan="3"><b>Upcoming Appointmemts</b></td></tr>';
		out+='<tr><td>Name</td><td>City</td><td>Zip</td><td>Rep</td><td>Date/Time</td></tr>';
		
		$.each(result,function(k,v){
			var cl=(k%2)?'even':'odd';
			cappts+='<tr class="'+cl+'"><td>'+v.cfullname+'</td><td>'+v.scity+'</td><td>'+v.szip+'</td><td>'+v.srname+'</td><td>'+v.cappt+'</td></tr>';
		});
		
		out+='</table>';
		*/
	//});
	
	if (typeof(cappts)=='object') {
		out+='<p><table width="425px">';
		out+='<tr><td colspan="3"><b>Upcoming Appointmemts</b></td></tr>';
		out+='<tr><td>Name</td><td>City</td><td>Zip</td><td>Rep</td><td>Date/Time</td></tr>';
		
		$.each(cappts,function(k,v){
			var cl=(k%2)?'even':'odd';
			out+='<tr class="'+cl+'"><td>'+v.cfullname+'</td><td>'+v.scity+'</td><td>'+v.szip+'</td><td>'+v.srname+'</td><td>'+v.cappt+'</td></tr>';
		});
		
		out+='</table>';
	}
	
	return out;
}

function displayCallbackAdjustDialog(cid,el) { 
	$("#jms_callbadjust_dialog").empty().dialog("close").remove();
	$('body').append('<div id="jms_callbadjust_dialog" style="display:none"></div>');
	
	var dialog = $("#jms_callbadjust_dialog").dialog({
	   dialogClass: 'noTitleDialog',
	   title : 'Adjust Appointment',
	   open : openCallbackDatePick(cid,el),
	   close : closeCallbackDatePick(),
	   autoOpen: false,
	   resizable: false,
	   modal: true,
	   width: 310,
	   height: 70,
	   position: {
		   my: 'middle top',
		   at: 'middle bottom',
		   of: el
	   }
	}).dialog("open");
	
	return false;
}

function openCallbackDatePick(cid,el) {
	$.getJSON('subs/ajax_lead_view_req.php?call=getCallback&optype=json&cid='+cid, function(result){
		$("#jms_callbadjust_dialog").html(CallbackForm(cid,result));
		$('button').button();
	});
}

function closeCallbackDatePick() {
	$("#jms_callbadjust_dialog").empty().dialog("close").remove();
}

function CallbackForm(cid,data) {
	var n = new Date();
	var cyr=n.getFullYear();
	var fmo=12;
	var fda=31;
	var fyr=new Date().getFullYear();
	
	out='';
	out+='<form id="frmadjCallback">';
	out+='<input type="hidden" id="holdcid" value="'+cid+'">';
	out+='<table><tr><td>Month</td><td>Day</td><td>Year</td><td></td></tr><tr>';
	out+='<td><select id="holdmo">';
	
	for (m=0;m<=fmo;m++) {
		out+=(data.mo==m)?'<option value="'+m+'" SELECTED>'+m+'</option>':'<option value="'+m+'">'+m+'</option>';
	}
	
	out+='</select></td>';
	out+='<td><select id="holdda">';
	
	for (d=0;d<=fda;d++) {
		out+=(data.da==d)?'<option value="'+d+'" SELECTED>'+d+'</option>':'<option value="'+d+'">'+d+'</option>';
	}
	
	out+='</select></td>';
	out+='<td><select id="holdyr">';
	
	for (y=(fyr-2);y<=(fyr+1);y++) {
		
		out+=(data.yr==y || cyr==y)?'<option value="'+y+'" SELECTED>'+y+'</option>':'<option value="'+y+'">'+y+'</option>';
	}
	
	out+='</select></td>';
	out+='<td><button>Update</button></td><td><button class="btnCancelCallbackAdjust">Cancel</button></td>';
	out+='</tr></table></form>';
	return out;
}

function processEmailTemplate(cid,etid,el) {
	var vbme=null;
	
	if ($('#bmeEmailBody').length > 0) {
		var bme=$('#bmeEmailBody').html();
		if (bme.length > 0) {
			var vbme=bme;
			//alert(vbme);
		}
	}
	
	$("#jms_emailsend_dialog").empty().html('<img src="images/mozilla_blu.gif"> Sending...').dialog("height","100px");
	
	$.ajax({
		cache:false,
		type : 'POST',
		url : 'subs/ajax_previewtemp.php',
		dataType : 'html',
		data: {
			call : 'processEmailTemplate',
			sysCID : cid,
			etid : etid,
			sbme: vbme
		},
		success : function(data){
			$("#jms_emailsend_dialog").empty().html(data).dialog("height","auto");
			getLeadCommentList(cid,el);
		},
		error : function(XMLHttpRequest, textStatus, errorThrown) {
			$("#jms_emailsend_dialog").empty().html(textStatus).show();
		}
	});
}

function displayEmailSendDialog(cid,etid)
{ 
   $("#jms_emailsend_dialog").empty().dialog("close").remove();
   $('body').append('<div id="jms_emailsend_dialog" style="display:none"></div>');
   
   var dialog = $("#jms_emailsend_dialog").dialog({
	  title : 'Send Email Template',
	  close : closeEmailSendDialog(),
	  open: getEmailTemplate(cid,etid),
	  autoOpen: false,
	  resizable: true,
	  modal: true,
	  width: 675,
	  height: 480
   }).dialog("open");
   
   return false;
}

function getEmailTemplate(cid,etid)
{
   $.ajax({
	  cache:false,
	  type : 'GET',
	  url : 'subs/ajax_previewtemp.php',
	  dataType : 'html',
	  data: {
		 sysCID : cid,
		 etid : etid
	  },
	  success : function(data){
		 $("#jms_emailsend_dialog").html(data);
		$('button').button();
	  },
	  error : function(XMLHttpRequest, textStatus, errorThrown) {
		 $("#jms_emailsend_dialog").html(textStatus).show();
	  }
   });
}

function closeEmailSendDialog()
{
   $("#jms_emailsend_dialog").empty().dialog("close").remove();
}

function saveLeadComment(cid,el)
{
   var ac=$('#addcomment').val();
   
   if ((!isNaN(cid)) && ac.length > 0)
   {
	  //alert(rn);
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
			 cmntflag: 0,
			 optype : 'json'
		 },
		 success : function(data){
			if (parseInt(data)!=0)
			{
			   $('#addcomment').val('');
			   getLeadCommentList(cid,el);
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
	  alert('No Comment Text or other error occurred');
	  return false;
   }
}

