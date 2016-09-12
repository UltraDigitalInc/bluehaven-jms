$(document).ready(function()
{
    var ajxscript	= 'subs/ajax_itemcost_req.php';
    var urlbrldr	= 'QB/bhsoap/url_builder.php';
    var pidprocr	= 'QB/bhsoap/QB_process_PID.php';
    var spinnerHTML = '<img src="images/mozilla_blu.gif"> Synchronizing... Please Wait';
    
    $('#SyncServiceItems').live('click',function() {
		//alert($('#usr_oid').val() + ':' + $('#usr_paction').val() + ':' + $('#usr_phsid').val());
        $('#textbox_CostConfigStatus').html(spinnerHTML).show();
		$.ajax({
			cache:false,
			type : 'GET',
			url : ajxscript,
			dataType : 'html',
			data: {
               call : 'itemcost',
			   oid : $('#usr_oid').val(),
               qact : $('#usr_paction').val(),
               multi : 1
			},
			success : function(data){
				$('#textbox_CostConfigStatus').html(data).show();
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
			   $('#textbox_CostConfigStatus').html(updateTEXT).show();
			}
		});
	});
    
    /*
    $('#SyncServiceItem').live('click',function() {
		//alert($('#oid').val() + ':' + $('#qaction').val() + ':' + $('#iid').val());
        $('#textbox_SingleCostConfigStatus').html(spinnerHTML).show();
		$.ajax({
			cache:false,
			type : 'POST',
            url : ajxscript,
			dataType : 'html',
			data: {
               call : 'itemcost',
               subq : 'SyncServiceItems',
			   oid : $('#oid').val(),
               qact : $('#qaction').val(),
               'pid[]' : $('#iid').val(),
               showout : 1
			},
			success : function(data){
                $('#textbox_SingleCostConfigStatus').html(data).show();
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
                $('#textbox_SingleCostConfigStatus').html(updateTEXT).show();
			}
		});
	});
    */
    
    $('#SyncMaterialItems').live('click',function() {
        $('#textbox_CostConfigStatus').html(spinnerHTML).show();
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
               call : 'itemcost',
			   oid : $('#usr_oid').val(),
               qact : $('#usr_pactionM').val(),
               multi : 1
			},
			success : function(data){
				$('#textbox_CostConfigStatus').html(data).show();
                //alert(data);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
			   $('#textbox_CostConfigStatus').html(textStatus).show();
			}
		});
        
        return true;
	});
    
    $('#SyncInventoryItems').live('click',function() {
        $('#textbox_CostConfigStatus').html(spinnerHTML).show();
		$.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
               call : 'itemcost',
			   oid : $('#usr_oid').val(),
               qact : $('#usr_pactionI').val(),
               multi : 1
			},
			success : function(data){
				$('#textbox_CostConfigStatus').html(data).show();
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
			   $('#textbox_CostConfigStatus').html(updateTEXT).show();
			}
		});
	});
    
    /*
    $('#SyncMaterialItem').live('click',function() {
        $('#textbox_SingleInventoryStatus').html(spinnerHTML).show();
		$.ajax({
			cache:false,
			type : 'GET',
			url : pidprocr,
			dataType : 'html',
			data: {
			   oid : $('#oid').val(),
               qact : $('#qaction').val(),
               'pid[]' : $('#iid').val(),
               showout : 1
			},
			success : function(data){
				$('#textbox_SingleInventoryStatus').html(data).show();
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
			   $('#textbox_SingleInventoryStatus').html(updateTEXT).show();
			}
		});
	});
    
    $('#SyncInventoryItem').live('click',function() {
		//alert('Submitted ' + $('#oid').val());
        $('#textbox_SingleInventoryStatus').html(spinnerHTML).show();
		$.ajax({
			cache:false,
			type : 'GET',
			url : pidprocr,
			dataType : 'html',
			data: {
			   oid : $('#oid').val(),
               qact : $('#qaction').val(),
               'pid[]' : $('#iid').val(),
               showout : 1
			},
			success : function(data){
				$('#textbox_SingleInventoryStatus').html(data).show();
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
			   $('#textbox_SingleInventoryStatus').html(updateTEXT).show();
			}
		});
	});
    */
});