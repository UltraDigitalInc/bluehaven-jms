$(document).ready(function()
{
    var ajxhlpscript        = 'subs/ajax_help_nodes_req.php';
    
    $('.getHelpNode').css('cursor', 'pointer');
    
    $(".getHelpNode").live('click',function(event) {
        event.preventDefault();
        displayHelpDialog($(this));
    });
    
    function displayHelpDialog(el)
    {
        $("#jms_help_dialog").dialog("close").remove();
        $('body').append('<div id="jms_help_dialog" style="display:none"></div>');
        
        getHelpData(el);
        
        var dialog = $("#jms_help_dialog").dialog({
            title : el.attr('title'),
            autoOpen: false,
            resizable: true,
            modal: false,
            width: 350,
            height: 300,
            close: closeHelpDialog(),
            position: {
                my: 'right top',
                at: 'right bottom',
                of: el
            },
        }).dialog("open");
        
        return false;
    }
    
    function getHelpData(el)
    {
        $.ajax({
			cache:true,
			type : 'POST',
			url : ajxhlpscript,
			dataType : 'html',
			data: {
				call : 'helpnodes',
				subq : 'getHelpNode',
				nodeid : el.attr('id'),
				optype : 'table'
			},
			success : function(data){
				$('#jms_help_dialog').html(data);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				alert(textStatus);
			}
		});
    }
    
    function closeHelpDialog()
    {
        $("#jms_help_dialog").remove();
        return false;
    }
});