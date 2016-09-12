$(document).ready(function() {
	$('button').button();
	$('.adjustAppt').css('color','blue');
	$('.adjustCallb').css('color','blue');
	$('.cmntCnt').css('color','blue');
	$('.pointer').css('cursor','pointer');
	
	$('.leadCommentDialog').live('click',function(e){
		displayLeadCommentDialog($(this));
	});
   
	$('.setpointer').live('hover',function(e) {
			$(this).css('cursor','pointer');
		}, function() {
			$(this).css('cursor','auto');
		return false;
	});
	
	$('.adjustAppt').live('click',function() {
		var prw=$(this).parent().parent().parent().parent().parent().parent();
		var cid=parseInt(prw.children('.LeadCID').html());

		if (!isNaN(cid)) {
			displayApptAdjustDialog(cid,$(this));
		}
		else {
			alert('Customer ID Error: ' + cid);
		}
	});
	
	$('.adjustCallb').live('click',function() {
		var prw=$(this).parent().parent().parent().parent().parent().parent();
		var cid=parseInt(prw.children('.LeadCID').html());
		
		if (!isNaN(cid)) {
			displayCallbackAdjustDialog(cid,$(this));
		}
		else {
			alert('Customer ID Error: ' + cid);
		}
	});
	
	$('.extMenuDialog').live('click',function(event) {
		var cid=$(this).parent().parent().children('.LeadCID').html();
		displayExtMenuDialog(cid,$(this));
	});
	
	$('#frmadjAppt').live('submit',function(event) {
		event.preventDefault();
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
					astate : 'search',
					optype : 'json'
				},
				success : function(data){
					closeApptDatePick();
					var dt=data.date+' '+data.time;
					$('#appt'+appt_cid).html(dt).parent().removeClass().addClass(data.lclass);
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
		
		if (!isNaN(hold_cid) && (!isNaN(hold_mo) && hold_mo!=0)){
		
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
					astate : 'search',
					optype : 'json'
				},
				success : function(data){
					closeCallbackDialog();
					$('#callb'+hold_cid).html(data.date).parent().removeClass().addClass(data.lclass);
				},
				error : function(XMLHttpRequest, textStatus, errorThrown) {
				   alert(textStatus);
				}
			});
		}
	});
	
	$('.btnCancelApptAdjust').live('click',function(e){
		e.preventDefault();
		closeApptDatePick();
	});
	
	$('.btnCancelCallbackAdjust').live('click',function(e) {
		e.preventDefault();
		closeCallbackDialog();
	});
	
	function displayApptAdjustDialog(cid,el) {
		//alert(cid);
		$("#jms_apptadjust_dialog").empty().dialog("close").remove();
		$('body').append('<div id="jms_apptadjust_dialog" style="display:none"></div>');
		
		var dialog = $("#jms_apptadjust_dialog").dialog({
		   dialogClass: 'noTitleDialog',
		   open : openApptDatePick(cid,el),
		   close : closeApptDatePick(),
		   autoOpen: false,
		   resizable: false,
		   modal: true,
		   width: 450,
		   height: 70,
		   position: {
			   my: 'middle top',
			   at: 'middle bottom',
			   of: el
		   }
		}).dialog("open");
		
		return false;
	}
	
	function openApptDatePick(cid,el)
	{
		$.getJSON('subs/ajax_leads_req.php?call=leads&subq=getAppt&optype=json&cid='+cid, function(result){
			$("#jms_apptadjust_dialog").html(AppointForm(cid,result));
			$('button').button();
		});
	}
	
	function closeApptDatePick()
	{
		$("#jms_apptadjust_dialog").empty().dialog("close").remove();
	}
	
	function displayCallbackAdjustDialog(cid,el)
	{ 
		$("#jms_callbadjust_dialog").empty().dialog("close").remove();
		$('body').append('<div id="jms_callbadjust_dialog" style="display:none"></div>');
		
		var dialog = $("#jms_callbadjust_dialog").dialog({
		   dialogClass: 'noTitleDialog',
		   open : openCallbackDatePick(cid,el),
		   close : closeCallbackDialog(),
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
	
	function openCallbackDatePick(cid,el)
	{
		$.getJSON('subs/ajax_lead_view_req.php?call=getCallback&optype=json&cid='+cid, function(result){
			$("#jms_callbadjust_dialog").html(CallbackForm(cid,result));
			$('button').button();
		});
	}
	
	function closeCallbackDialog()
	{
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
	
	function displayExtMenuDialog(cid,el)
	{ 
		$("#jms_ExtMenu_dialog").empty().dialog("close").remove();
		$('body').append('<div id="jms_ExtMenu_dialog" style="display:none"></div>');
		
		var dialog = $("#jms_ExtMenu_dialog").dialog({
		   dialogClass: 'noTitleDialog',
		   open : getExtMenuData(cid,el),
		   close : closeExtMenu(),
		   autoOpen: false,
		   resizable: false,
		   modal: false,
		   width: 150,
		   height: 75,
		   position: {
			   my: 'right top',
			   at: 'left bottom',
			   of: el
		   }
		}).dialog("open");
	
		$('.setpointer').css('cursor','pointer').css('color','blue');
		
		return false;
	}
	
	function getExtMenuData(cid,el)
	{
		$.getJSON('subs/ajax_leads_req.php?call=leads&subq=getExtLeadMenu&optype=json&cid='+cid, function(result){
			$('#jms_ExtMenu_dialog').html(ExtMenu(result));
		});
	}
	
	function ExtMenu(data)
	{
		var out='';
		
		$.each(data, function(k,v){
			out+='<span class="setpointer"><span class=".ExtMenuType">'+v.type+'</span>: <span class=".ExtMenuType">'+v.id+'</span></span><br>';
		});
		
		return out;
	}
	
	function closeExtMenu()
	{
		$("#jms_ExtMenu_dialog").empty().dialog("close").remove();
	}
	
	function AppointForm(cid,data)
	{
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
		return out;
	}
	
	function setColorClass(el,lclass) {
		var pel=el.parent().parent();
		pel.attr('class',lclass);
	}
	
	function displayLeadCommentDialog(el)
	{ 
	   $("#jms_leadcomment_dialog").empty().dialog("close").remove();
	   $('body').append('<div id="jms_leadcomment_dialog" style="display:none"></div>');
	   
	   var lnm=el.parent().parent().children('.allnames').children('.clname').html();
	   
	   var dialog = $("#jms_leadcomment_dialog").dialog({
		  title : 'Lead Comments: '+ lnm,
		  open : getLeadCommentList(el),
		  close : closeLeadCommentDialog(),
		  autoOpen: false,
		  resizable: true,
		  modal: true,
		  width: 550,
		  height: 300,
		  position: {
			  my: 'right top',
			  at: 'left bottom',
			  of: el
		  }
	   }).dialog("open");
	   
	   return false;
	}
	
	function closeLeadCommentDialog()
	{
	   $("#jms_leadcomment_dialog").empty().dialog("close").remove();
	}
	
	function getLeadCommentList(el)
	{
	   var cid=parseInt(el.parent().parent().children('.viewForms').children('.viewLeadForm').children('.sysCID').val());
	   if (!isNaN(cid) && cid!=0)
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
				$('#jms_leadcomment_dialog').html(data);
			 },
			 error : function(XMLHttpRequest, textStatus, errorThrown) {
				$('#jms_leadcomment_dialog').html(textStatus).show();
			 }
		 });
	   }
	}
});