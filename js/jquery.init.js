$(document).ready(function()
{	
    $('#new_msg_notify').hide();
    
    var AP_CB_menu_tab_id   = parseInt($.cookie("AP_CB_menu_tab")) || 0;
	var state_obj_hidden   	= parseInt($.cookie("state_obj_hidden")) || 0;
    var new_msg_ack         = parseInt($.cookie("new_msg_ack")) || 0;
    var new_msg_txt         = '<strong>You have Unread Message(s)<br>Click the Message Envelope on the Menu to Read your Messages.</strong>!';
	var ajxhlpscript        = 'subs/ajax_help_nodes_req.php';
	
	$('button').button();
	
    $('.provided_lead_fwd')
        .click(function()
        {
            $.cookie("AP_CB_menu_tab", 3);
        }
    );

    if (new_msg_ack!=1)
    {
        $('#new_msg_notify').dialog({
                bgiframe: true,
                autoOpen: true,
                position: 'top',
                resizeable: false,
                width: 225,
                modal: false,
                title: 'You have Unread Messages',
                open: function () { 
                        $(this)
                        .parents(".ui-dialog:first")
                        .find(".ui-dialog-titlebar")
                        .addClass("ui-state-highlight ui-corner-all"); 
                    },
                buttons: {
                    View:function() {
                    $.cookie("new_msg_ack", 1);
                    $('#show_jms_messages').submit();
                    $(this).dialog('close');
                   },
                    Acknowledge:function() {
                    $.cookie("new_msg_ack", 1);
                    $(this).dialog('close');
                   },
                   Close:function() {
                    $(this).dialog('close');
                   }
                }
        });
    }
    
    $('#overlay1').dialog({
			bgiframe: true,
			autoOpen: true,
            resizeable: false,
			position: 'top',
			height: 300,
            width: 600,
			modal: true,
            buttons: {
               Close: function() {
				$(this).dialog('close');
			   }
            }
	});
    
    $('#mainviewer').tabs();
    
    $("#myTable").tablesorter(
    {
        widgets: ['zebra'],
        headers:
        {
            0:
            {
                sorter: false
            },
            10:
            {
                sorter: false
            }
        }
    }
    );
    
    $("#jobProgress").tablesorter(
    {
        widgets: ['zebra'],
        sortList: [[1,0]],
        headers:
        {
            2:
            {
                sorter: false
            },
            7:
            {
                sorter: false
            },
            8:
            {
                sorter: false
            },
            9:
            {
                sorter: false
            },
            10:
            {
                sorter: false
            },
            11:
            {
                sorter: false
            },
            12:
            {
                sorter: false
            },
            13:
            {
                sorter: false
            },
            14:
            {
                sorter: false
            },
            15:
            {
                sorter: false
            },
            16:
            {
                sorter: false
            },
            17:
            {
                sorter: false
            },
            18:
            {
                sorter: false
            },
            19:
            {
                sorter: false
            }
        }
    }
    );
    
    $("#TickleTable").tablesorter(
    {
        widgets: ['zebra'],
        headers:
        {
            0:
            {
                sorter: false
            },
            10:
            {
                sorter: false
            }
        }
    }
    );
    
    $("#myTrackTable").tablesorter({
        widgets: ['zebra'],
        headers: {
            8: {
                sorter: false
            },
            9: {
                sorter: false
            },
            11: {
                sorter: false
            }
        }
    });
    
    $('#d1').datepicker({changeMonth: true, changeYear: true});
    $('#d2').datepicker({changeMonth: true, changeYear: true});
    $('#d3').datepicker({changeMonth: true, changeYear: true});
    $('#d4').datepicker({changeMonth: true, changeYear: true});
    $('#d5').datepicker({changeMonth: true, changeYear: true});
    $('#d6').datepicker({changeMonth: true, changeYear: true});
    $('#d7').datepicker({changeMonth: true, changeYear: true});
    $('#d8').datepicker({changeMonth: true, changeYear: true});
    $('#d9').datepicker({changeMonth: true, changeYear: true});
    $('#d10').datepicker({changeMonth: true, changeYear: true});
    $('#d11').datepicker({changeMonth: true, changeYear: true});
    $('#d12').datepicker({changeMonth: true, changeYear: true});
    $('#d13').datepicker({changeMonth: true, changeYear: true});
    $('#d14').datepicker({changeMonth: true, changeYear: true});
    $('#d15').datepicker({changeMonth: true, changeYear: true});
    $('#d16').datepicker({changeMonth: true, changeYear: true});
    $('#d17').datepicker({changeMonth: true, changeYear: true});
    $('#d18').datepicker({changeMonth: true, changeYear: true});
    $('#d19').datepicker({changeMonth: true, changeYear: true});
    $('#d20').datepicker({changeMonth: true, changeYear: true});
    
    $('#hdate').datepicker({changeMonth: true, changeYear: true});
    
    $('#datep[*]').datepicker({changeMonth: true, changeYear: true});
    
    $('#dZ1').datepicker();
    $('#dZ2').datepicker({
        numberOfMonths: 3,
		showButtonPanel: true
        });
    
    $('#cdate').datepicker();
    
    $("#ccomments").focus();
    $("#cpname").focus();
    
    $('#searchaccordion').accordion({
       collapsible:true
    });
    
    $('#searchtabs').tabs();
    $('#resulttabs').tabs();
    $('#tickletabs').tabs();
    //$('#createcontract').tabs();
    $('#filelist').tabs();
    $('#storageselect').tabs();
    
    $('.buttons').tooltip();
    $('.JMStooltip').tooltip({track: true,showURL: false});
    $('.bboxbc').tooltip({showURL: false});
    $('.transnb_button').tooltip({showURL: false});
    $('.buttondkgrypnl80').tooltip();
    $('.buttondkgrypnl70').tooltip();
    $('.buttondkgrypnl60').tooltip();
    $('.buttondkgrypnl').tooltip();
    
    $("#pswdtimeout").dialog({
       autoOpen: true,
       draggable: false,
       resizable:false,
       bgiframe: true,
       height: 200,
       width: 450,
       modal: true
    });
    
    $("#leadcomment").dialog({
       autoOpen: false,
       draggable: false,
       resizable:false,
       width: 400,
       bgiframe: true,
       modal: false
    });
    
    $('#sendmsgfrm').hide();
    
    $("#sendmsgfrm").dialog({
       autoOpen: false,
       draggable: false,
       resizable:false,
       height: 250,
       width: 400,
       bgiframe: true,
       modal: false
    });
    
    //$('.lifeform').hover();
    
    $('#addcomment').click(function() {
       $('#leadcomment').dialog('open');
    });
    
    $('#showmsgfrm').click(function() {
       $('#sendmsgfrm').dialog('open');
    });
    
    $('td.pullrec').click(function() {
       $(this).find('#recid').parent().submit();
    });
    
    $('tr.white').click(function() {
       //$(this).find('#recid').parent().submit();
    });
    
    $('tr.ltgray').click(function() {
       //$(this).find('#recid').parent().submit();
    });
    
    $('tr.wh_und').click(function() {
       //$(this).find('#recid').parent().submit();
    });
    
    $('tr.yel_und').click(function() {
       //$(this).find('#recid').parent().submit();
    });
    
    $('tr.grn_und').click(function() {
       //$(this).find('#recid').parent().submit();
    });
    
    $('tr.red_und').click(function() {
       //$(this).find('#recid').parent().submit();
    });
    
    $('tr.odd').click(function() {
       $(this).find('#recid').parent().submit();
    });
    
    $('tr.even').click(function() {
       $(this).find('#recid').parent().submit();
    });
    
    $('.formatCurrency').blur(function(){
        $(this).formatCurrency({
            colorize:true,
            groupDigits:false,
            symbol:'',
            negativeFormat:'-%n'
        });
    });
	
	$('.objDisabled').live('click',function() {
        alert('This function has been disabled');
    });
	
	$('#objSetHidden').live('click',function() {
        //alert('This function has been disabled');
		$('.objHidable').hide();
    });
	
	$('#objSetShow').live('click',function() {
        //alert('This function has been disabled');
		$('.objHidable').show();
    });
});