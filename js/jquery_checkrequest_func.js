$(document).ready(function(){    
    var retdata = '<br>Retrieving <img src="../images/mozilla_blu.gif">';
    var ajxproc = '../subs/ajax_checkrequest_req.php';
    
    
    $('#OpenCRMaster').click(function() {
        //alert('Test');
        $('#CRMasterDialog').dialog('open');
    });
    
    $("#CRMasterDialog").dialog({
        bgiframe: true,
        autoOpen: false,
        resizeable: false,
        draggable: false,
        height: 450,
        title: 'New Check Request',
        width: 400,
        modal: false,
        buttons: {
            Submit: function() {
                alert('TestSubmit');
            },
            Close: function() {
                $(this).dialog('close');
            }
        }
    });
    
    $('#CRSearch').click(function() {
        //alert($('#cr_sval').val());
        //alert($('#cr_pptype').val());
        $('#CRResults').html(retdata).show(500);
        $.ajax({
                cache:false,
                type : 'POST',
                url : ajxproc,
                dataType : 'html',
                data: {
                   call : 'CheckRequest',
                   subq : 'get_Search_List',
                   ssid : $('#SessHash').val(),
                   cr_sval: $('#cr_sval').val(),
                   cr_pptype: $('#cr_pptype').val(),
                   optype : 'table'
                },
                success : function(data){
                   $('#CRResults').html(data).show(500);
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                   $('#CRResults').html(textStatus + ' - ' + errorThrown).show(500);
                }
        });
    });
    
    $('#CRPending').click(function() {
        $('#CRResults').html(retdata).show(500);
        $.ajax({
                cache:false,
                type : 'POST',
                url : ajxproc,
                dataType : 'html',
                data: {
                   call : 'CheckRequest',
                   subq : 'get_Pending_List',
                   ssid : $('#SessHash').val(),
                   optype : 'table'
                },
                success : function(data){
                   $('#CRResults').html(data).show(500);
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                   $('#CRResults').html(textStatus + ' - ' + errorThrown).show(500);
                }
        });
    });
    
    $('#CRProcessed').click(function() {
        $('#CRResults').html(retdata).show(500);
        $.ajax({
                cache:false,
                type : 'POST',
                url : ajxproc,
                dataType : 'html',
                data: {
                   call : 'CheckRequest',
                   subq : 'get_Processed_List',
                   ssid : $('#SessHash').val(),
                   optype : 'table'
                },
                success : function(data){
                   $('#CRResults').html(data).show(500);
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                   $('#CRResults').html(textStatus + ' - ' + errorThrown).show(500);
                }
        });
    });
});