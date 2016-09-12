$(document).ready(function()
{	
	var ajxscript	= 'subs/ajax_index_req.php';
	var sftabid		= parseInt($.cookie("sftabid")) || 0;
	var spinnerIMG	= '<img src="images/mozilla_blu.gif"> Retrieving...';
	
	$('#SystemMessageViewer').tabs({
		selected:sftabid,
		show: function(event,ui){
			var sftabid = ui.index;
			$.cookie("sftabid", sftabid);
			
			switch(sftabid)
			{
				case 0:
					$('#LeadReport').html(spinnerIMG).show(500);
					show_IndexData('LeadReport');
				break;
			 
				case 1:
					$('#CustServ').html(spinnerIMG).show(500);
					show_IndexData('CustServ');
				break;
				
				case 2:
					$('#SysAnn').html(spinnerIMG).show(500);
					show_IndexData('SysAnn');
				break;
				
				case 3:
					$('#ConList').html(spinnerIMG).show(500);
					show_IndexData('ConList');
				break;
			}
		}
	 });
	
	function show_IndexData(prc)
	{
		$.ajax({
			async: false,
			cache:false,
			type : 'GET',
			url : ajxscript,
			dataType : 'html',
			data: {
				call : 'index',
				subq : prc,
				optype : 'table'
			},
			success : function(data){
				$('#'+prc).empty().append(data);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				alert(textStatus);
			}
		})		
		return false;
	}
});