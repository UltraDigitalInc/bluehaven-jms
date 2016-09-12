$(document).ready(function() {
	$('button').button();
	
	$('#openEmailQueueDlg').click(function(e) {
		EmailQueueDlg($(this));
	});
	
	$('.emailqueue').click(function(e) {
        var chk = $('input.emailqueue:checked').length;
		
		if ($('#EmailQueueStat').length) {
			$('#EmailQueueStat').empty().text(chk+' Email Recipients will receive this Message');
		}
	});
});

function EmailQueueDlg(el) {
	var node=$('<div />',{'id':'emailqueue_dialog'});
	$.get('subs/ajax_leads_req.php?call=leads&subq=get_EmailTemplates', function(data){
        node.append(data);
    });		
	$('body').append(node);
	
	var dialog = $('#emailqueue_dialog').dialog({
		dialogClass: 'noTitleDialog',
		close : closeEmailQueue(),
		autoOpen: false,
		resizable: false,
		modal: false,
		width: 275,
		height: 130,
		position: {
			my: 'right top',
			at: 'left bottom',
			of: el
		},
		buttons: {
			'Submit': function() {
				procEmailQueue()
			},
			'Check All': function() {
				$('.emailqueue').prop('checked',true);
				var chk = $('input.emailqueue:checked').length;
				$('#EmailQueueStat').empty().text(chk+' Email Recipients will receive this Message');
				
			},
			'Cancel': function() {
				closeEmailQueue()
			}
		}
	}).dialog('open');
	
	$('.emailqueue').show();
	return false;
}

function procEmailQueue() {
	var etq=$('#emq_etid').val();
	if (etq!=0) {
		var emids=[];
		var emcnt=0;
		$.each($('input.emailqueue'),function(k,v){
			if (this.checked) {
				emids.push($(this).val())
				emcnt++;
			}
		});
		
		if (emcnt!=0) {
			$('#emailqueue_dialog').empty().html('<span><img src="images/jmsprocess.gif"> Queuing '+emcnt+' Emails...</span>');
			$.ajax({
				cache:false,
				type : 'POST',
				url : 'subs/ajax_leads_req.php',
				dataType : 'json',
				data: {
					'call' : 'leads',
					'subq' : 'save_EmailQueue',
					'optype' : 'json',
					'etid' : etq,
					'cid_ar': emids
				},
				success : function(data){					
					$("#emailqueue_dialog").empty().html(data.result).dialog("height","auto");
				},
				error : function(XMLHttpRequest, textStatus, errorThrown) {
					alert(textStatus);
				}
			});			
			//console.log(etq);
			//console.log(emids);
		}
		else {
			alert('Select Recipients');
		}
	}
	else {
		alert('Select a Template');
	}
}

function formEmailQueue() {	
	var $form=$('<form>',{'id':'frmEmailQueueProc'});
	
	//$.get('subs/ajax_leads_req.php?call=leads&subq=get_EmailTemplates', function(data){
    //    $('#emailqueue_dialog').append(data);
    //});
	
	$("#emailqueue_dialog").append($form);
}

function getEmailTemplates() {	
    $.get('subs/ajax_leads_req.php?call=leads&subq=get_EmailTemplates', function(data){
        $('#frmEmailQueueProc').append(data);
    });
}

function closeEmailQueue() {	
	$('.emailqueue').prop('checked',false);
	$('.emailqueue').hide();
	$("#emailqueue_dialog").dialog("close").empty().remove();
}
