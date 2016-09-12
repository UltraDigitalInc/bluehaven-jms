$(document).ready(function()
{
    var ajxscript ='subs/ajax_messages_req.php';
    var emailcl='images/email.png';
    var emailop='images/email_open.png';
	var emailal='images/email_new.png';
    
    $('.msgViewAll').click(function(){
        $('.msgHidden').toggle();
        $('.msgImg').attr('src',emailop);
		markmsgRead(0);
    });
    
    $('.msgView').click(function(event){
        event.preventDefault();
        
		var msgID =parseInt($(this).parent().parent().children('td.msgID').html());
        $('.msgHidden').hide();
        $('.msgImg').attr('src',emailcl);
        $(this).children('.msgImg').attr('src',emailop);
        $(this).parent().parent().children('td.msgText').children('div.msgHidden').show();
		markmsgRead(msgID);
    });
    
    $('#subfdbkmsg').click(function(event) {
        event.preventDefault();
        
        fdbkmsgDialog($(this));
    });

	function markmsgRead(i){
		$("#msgStatus").attr('src',emailcl);
		
		$.ajax({
            cache:false,
            type : 'POST',
            url : ajxscript,
            dataType : 'html',
            data: {
                call : 'markmsgRead',
                msgid : i,
                optype : 'table'
            }
        });
	}
	
    function fdbkmsgDialog(el)
    {
        $('#fdbkmsgDialog').empty().remove();
        $('body').append('<div id="fdbkmsgDialog"></div>');
        
        getfdbkForm();
        
        var dialog = 
        $('#fdbkmsgDialog').dialog({
            title: 'JMS FeedBack',
            bgiframe: true,
            autoOpen: false,
            resizeable: false,
            height: 400,
            width: 500,
            modal: true,
            position: {
                my: 'right top',
                at: 'right bottom',
                of: el
            },
            close: function()
            {
                closefdbkForm();  
            },
            buttons: {
                'Close': function() {
                    closefdbkForm();
                },
                'Send': function() {
                    sendfdbkmsg();
                }
            }
        }).dialog('open');
    }
    
    function closefdbkForm()
    {
        $('#fdbkmsgDialog').empty().remove();
        return false;
    }
    
    function getfdbkForm()
    {
        $.ajax({
			cache:false,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
				call : 'new_feedback',
				optype : 'table'
			},
			success : function(data){
				$('#fdbkmsgDialog').html(data);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				alert(textStatus);
			}
		});
        return false;
    }
    
    function sendfdbkmsg()
    {
        var cString = $("#ajx_mbody").val();
        if (cString.length > 0)
        {
            $(":button:contains('Send')").attr("disabled","disabled").addClass('ui-state-disabled');
            $.ajax({
                cache:false,
                type : 'POST',
                url : ajxscript,
                dataType : 'html',
                data: {
                    call : 'add_feedback',
                    ajx_msubject: 'JMS Feedback',
                    ajx_mbody: cString,
                    optype : 'table'
                },
                success : function(data){
                    $('#fdbkmsgDialog').html(data);
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(textStatus);
                }
            });
        }
        else
        {
            alert('Please enter a message.');
        }
        
        return false;
    }
});