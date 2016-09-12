$(document).ready(function() {
	$('button').button();
	$('.adjustAppt').css('color','blue');
	$('.adjustCallb').css('color','blue');
	$('.cmntCnt').css('color','blue');
	$('.pointer').css('cursor','pointer');
	
	if (typeof(lead_search_result)!='undefined') {
		showLeadResults();
	}
	
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
		var fyr=2012;
		
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
		
		for (y=(fyr-2);y<=(fyr+2);y++) {
			
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
		var fyr=2012;
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
		
		for (y=(fyr-2);y<=(fyr+2);y++) {
			
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

function showLeadResults() {
	//echo "          <table id=\"lead_result_table\" width=\"100%\">\n";
	var $table=$('<table>',{'id':'lead_result_table','width':'100%'});
	var $thdr=showResultTableHdr();
	$table.html($thdr);
	
	var i=1;
	$.each(lead_search_result,function(k,v){
		$table.append(showCustomerLine(i,v));
		i++;
	});
	
	$('#lead_result_container').html($table);
	$('button').button();
}

function showResultTableHdr() {
	var hdrs=['','Lead ID','Name','Phone','Site','Rep','Status','Dates','Lifecycle','Cmnts',''];
	var alns=['center','center','left','left','left','left','left','left','left','left','right']
	var $tr=$('<tr />',{'class':'tblhd'});
	
	$.each(hdrs,function(k,v){
		$tr.append($('<td />',{'align':alns[k],text:v}));
	});
	
	/*
	out+='<tr class="tblhd">';
    out+='	<td align="center"></td>';
    out+='	<td align="center"><b>Lead ID</b></td>';
    out+='	<td align="left" width="100"><b>Name</b></td>';
    out+='	<td align="left"><b>Phone</b></td>';
    out+='	<td align="left"><b>Site</b></td>';
    out+='	<td align="left"><b>Rep</b></td>';
	out+='	<td align="left" width="150"><b>Status</b></td>';
    out+='	<td align="left" width="110"><b>Dates</b></td>';
    out+='	<td align="left" title="JMS LifeCycle"><b>LifeCycle</b></td>';
    out+='	<td align="left" title="Total Comments for this Lead"><b>Cmnts</b></td>';
	out+='	<td class="noPrint" align="right"><span id="ls_res_cnt"></span> Result(s)</td>';
	*/
	
	out=$tr;
	return out;
}

function showCustomerLine(i,v) {
	//var out='';
	//var system=v.system;
	//console.log(v);
	var frm=openLeadForm(v.system);
	var src=frmtSrcing();
	var out=$('<tr />', {'class': (i%2) ? 'odd' : 'even'});
	out.append($('<td />',{'class':'pullrec','align':'center','valign':'top',text:i}));
	out.append($('<td />',{'class':'pullrec LeadCID','align':'center','valign':'top',text:v.system.cid}));
	out.append($('<td />',{'class':'pullrec allnames','align':'left','valign':'top','width':'100px',text:v.lead.fname+' '+v.lead.lname}));
	out.append($('<td />',{'class':'pullrec','align':'left','valign':'top',text:v.contact.home}));
	out.append($('<td />',{'class':'pullrec','align':'left','valign':'top',html:v.site.addr1+'<br>'+v.site.city+'<br>'+v.site.zip1}));
	out.append($('<td />',{'class':'pullrec','align':'left','valign':'top',text:v.srep.fname+' '+v.srep.lname}));
	out.append($('<td />',{'class':'pullrec','align':'left','valign':'top'}));
	out.append($('<td />',{'class':'pullrec','align':'left','valign':'top','width':'150px'}));
	out.append($('<td />',{'class':'pullrec','align':'center','valign':'top'}));
	out.append($('<td />',{'class':'pullrec','align':'center','valign':'top'}));
	out.append($('<td />',{'class':'pullrec viewForms noPrint','align':'center','valign':'top',html:frm}));
	
	/*
    out+='	<td class="pullrec" align="center" valign="top">TEST</td>';
    out+='	<td class="pullrec LeadCID" align="center" valign="top">CID</td>';
    out+='	<td class="pullrec allnames" align="left" valign="top" width="100px">NAMES</td>';
    out+='	<td class="pullrec" align="left" valign="top">CleanFormatPhones</td>';
    out+='	<td class="pullrec" align="left" valign="top">ADDRESS</td>';
    out+='	<td class="pullrec" align="left" valign="top">SREP</td>';
	out+='	<td class="pullrec" align="left" valign="top">showSrcing</td>';
    out+='	<td class="pullrec" align="left" valign="top" width="150px">showDates</td>';
    out+='	<td class="pullrec" align="center" valign="top">showLifeCycle</td>';
    out+='	<td class="pullrec" align="center" valign="top"><span class="setpointer leadCommentDialog"><span class="cmntCnt">CMNT</span></span></td>';
    out+='	<td class="pullrec viewForms noPrint" align="center" valign="top">FORM';
    */
	
	return out;
}

function frmtSrcing(d) {
	var out='';
	
	if (d.source==0) {
        out+='Source: bluehaven.com';
    }
    else if (d.source >= 1) {
        out+='Source: '+d.srcname;
    }
	
    out+='<br>';
    out+=(d.stage==6)?'Result: '+d.resname:'Result: '+d.resname;	
	out+='<br>';
	//out+='Added: '.date('m/d/y',strtotime($src['dates']['system']['added']));
	
	return $out;
}

function openLeadForm(s) {
	var $form=$('<form />',{'class':'viewLeadForm','method':'POST'});
	$form.append($('<input>',{'type':'hidden','name':'action','value':'leads'}));
	$form.append($('<input>',{'type':'hidden','name':'call','value':'view'}));
	$form.append($('<input>',{'type':'hidden','name':'cid','value':s.cid}));
	$form.append($('<input>',{'type':'hidden','name':'uid','value':s.uid}));
	$form.append($('<button />',{'class':'btnsysmenu',text:'Open Lead'}));
	/*
	echo "                     	    <form class=\"viewLeadForm\" method=\"POST\">\n";
    echo "                     		    <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
    echo "                     		    <input type=\"hidden\" name=\"call\" value=\"view\">\n";
    echo "                     		    <input class=\"sysCID\" type=\"hidden\" name=\"cid\" value=\"".$d['system']['cid']."\">\n";
    echo "                     		    <input type=\"hidden\" name=\"uid\" value=\"".$d['system']['uid']."\">\n";
	echo "<button class=\"btnsysmenu\">Open Lead</button>\n";
    echo "</form>\n";
    */
	
	return $form;
}

function showCustomerLineOLD(lcnt,tsdate,d) {
	var out='';
	var lclass=(lcnt%2)?'even':'odd';	
	out+='<tr class="'+lclass+'">';
    out+='	<td class="pullrec" align="center" valign="top">'+lcnt+'</td>';
    out+='	<td class="pullrec LeadCID" align="center" valign="top">'+d.system.cid+'</td>';
    out+='	<td class="pullrec allnames" align="left" valign="top" width="100px"><span class="clname">'+d.lead.lname+'</span><br>'+d.lead.fname+'</td>';
    out+='	<td class="pullrec" align="left" valign="top">CleanFormatPhones</td>';
    out+='	<td class="pullrec" align="left" valign="top">'+d.site.addr1+'\n\r'+d.site.city+'\n\r'+d.site.zip1+'</td>';
    out+='	<td class="pullrec" align="left" valign="top"><font class="'+d.format.fstyle+'">'+d.srep.fname+'\n\r'+d.srep.lname+'</font></td>';
	out+='	<td class="pullrec" align="left" valign="top">showSrcing</td>';
    out+='	<td class="pullrec" align="left" valign="top" width="150px">showDates</td>';
    out+='	<td class="pullrec" align="center" valign="top">showLifeCycle</td>';
    out+='	<td class="pullrec" align="center" valign="top"><span class="setpointer leadCommentDialog"><span class="cmntCnt">'+d.system.lcmtcnt+'</span></span></td>';
    out+='	<td class="pullrec viewForms noPrint" align="center" valign="top">';
    out+='		<form class="viewLeadForm" method="POST">';
    out+='			<input type="hidden" name="action" value="leads">';
    out+='			<input type="hidden" name="call" value="view">';
    out+='			<input class="sysCID" type="hidden" name="cid" value="'+d.system.cid+'">';
    out+='			<input type="hidden" name="uid" value="'+d.system.uid+'">';
	out+='			<button class="btnsysmenu">Open Lead</button>';
    out+='		</form>';
    out+='	</td>';
    out+='</tr>';
}