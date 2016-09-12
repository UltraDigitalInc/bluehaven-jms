$(document).ready(function() {
    var syscid  = parseInt($('#usr_cid').val())||0;
    var fin	    = $('#finalEl');
	var wel	    = $('#tblWrap');
    var ach     = false;
    var cch     = false;
    var ccval   = 'A';
    var sCDVal  = $('#getConstrDates').val();
    
    if (fin.length) {
		wel.show(500);
	}
    
    if (sCDVal!=0) {
        getConstructionDates(sCDVal);
        cch=true;
    }
    
    $('.datepick').datepicker();
    $('.datepick_dd').datepicker();
    
    $('#osCDDisplay').live('click',function(e){
        e.preventDefault();        
        if (!cch) {
            cch=true;
            $('#osCDDisplayWrap').show();
            $('#osCDDisplay').text('Hide Dates');
        }
        else {
            cch=false;
            $('#osCDDisplayWrap').hide();
            $('#osCDDisplay').text('Show Dates');
        }
    });
    
    $('.osCmntCntrl').live('click',function(e){
        e.preventDefault();
        var cval=$(this).val();
        var tval=$(this).attr('id');
        ccval=cval;
        $('.osCmntCntrl').removeAttr("checked");
        $('.btnCmntCntrl').css('background-color','#dddddd');
        $(this).attr("checked", "checked");
        $('label[for="'+tval+'"]').css('background-color','#bbbbbb');
        
        CmntCntrlDisplay(ccval);
    });
    
    if ($('#OneSheetComments').length) {
        var el=$('#OneSheetComments');
        getOneSheetComments(syscid,el);
    }
    
    $('#refreshOneSheetComments').live('click',function() {
        var el=$('#OneSheetComments');
        getOneSheetComments(syscid,el);
        CmntCntrlDisplay(ccval);
    });
    
    $('#getConstrDates').live('change',function(e){
        e.preventDefault();
        var jid=$(this).val();
        getConstructionDates(jid);
        cch=true;
    });
    
    $('.setpointer').live('hover',function() {
            $(this).css('cursor','pointer');
        }, function() {
            $(this).css('cursor','auto');
        return false;
    });
    
    $('.texpandtext').live('click',function() {
        $(this).hide();
        $(this).parent().children('span.thiddentext').show();
    });
    
    $('#expandOneSheetComments').live('click',function() {
        $('span.texpandtext').hide();
        $('span.thiddentext').show();
    });
    
    $('#saveOneSheetComment').live('click',function(e){
        e.preventDefault();
        var el=$('#OneSheetComments');
        saveOneSheetComment(syscid,el);
    });
    
    $('.ClearDateLine').live('click',function(e){
        e.preventDefault();
        var jid = $('#getConstrDates').val();
        var tid = $(this).attr('id');
        var phsn= $(this).parent().parent().children('.phsname').html();
        var conf= confirm('You are about to Clear ALL entries for the '+phsn+' Phase\n\nClick Ok to Continue');
        
        if (conf) {
            clearCDDateLine(jid,tid,$(this));
        }
    });
    
    $('#clearDigDate').live('click',function(e){
        e.preventDefault();
        var jid = $('#getConstrDates').val();
        var conf= confirm('You are attempting to clear the Dig Date.\n\n\This action will clear Commissions for this Job.\n\nClick Ok to Continue');
        
        if (conf) {
            //alert(jid)
            clearDigDate(jid);
        }
    });
    
    $('.CDCurrencyField').live('blur',function(e){
        e.preventDefault();
        var jid = $('#getConstrDates').val();
        var vid = $(this).val();
        var tid = $(this).attr('id');
        var stid= tid.split("_");
        var vtid= $('#val_ramt_'+stid[2]).html();
        
        if (vid!=vtid) {
            updateRecvAmt(jid,stid,vid,$(this));
        }
    });
    
    $('#saveOSComment').live('click',function(e){
        e.preventDefault();

        var msel = $('#cmntflag').val();
        var mtxt = $('#mtext').val();
        var cpid = 0;
        
        if (msel != 0) {
            if (mtxt.length > 0 ) {
                saveOSComment(syscid,msel,mtxt);
            }
            else {
                alert('No Comment Text');
            }
        }
        else {
            alert('Select a Comment Type');
        }
    });
    
    $('.resfolOSComment').live('click',function(e){
        e.preventDefault();
        var cpid=parseInt($(this).text());
        OneSheetCommentDialog(syscid,cpid,$(this));
    });
    
    $('#openCommentDialog').live('click',function(e){
        e.preventDefault();
        var cpid='';
        OneSheetCommentDialog(syscid,cpid,$(this));
    });
});

function clearCDDateLine(jid,tid,el) {
    $.ajax({
        cache:false,
        type : 'POST',
        url : 'subs/ajax_onesheet_req.php',
        dataType : 'json',
        data: {
            call : 'clearConstructionDateLine',
            jid : jid,
            tid :  tid,
            optype : 'json'
        },
        success : function(data) {
            if (data.error) {
                alert('Error:\n\n'+data.result);
            }
            else {
                getConstructionDates(jid);
            }
        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
            alert(textStatus);
        }
    });
    return true;
}

function clearDigDate(jid) {
    $.ajax({
        cache:false,
        type : 'POST',
        url : 'subs/ajax_onesheet_req.php',
        dataType : 'json',
        data: {
            call : 'clearConstructionDigDate',
            jid : jid,
            optype : 'json'
        },
        success : function(data) {
            if (data.error) {
                alert('Error:\n\n'+data.result);
            }
            else {
                getConstructionDates(jid);
            }
        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
            alert(textStatus);
        }
    });
    return true;
}

function updateRecvAmt(jid,stid,vid,el) {
    var phs=parseInt(stid[2]);
    var st='#status_'+phs;
    var vst=$('#val_ramt_'+phs);
    
    $('.clear_phase_status').empty();
    
    $.ajax({
        cache:false,
        type : 'POST',
        url : 'subs/ajax_onesheet_req.php',
        dataType : 'json',
        data: {
            call : 'saveConstructionRecvAmt',
            jid : jid,
            phsid : phs,
            amt : vid,
            optype : 'json'
        },
        success : function(data){
            if (data.error) {
                el.val(vst.html());
                alert('Error:\n\n'+data.result);
            }
            else {
                vst.html(el.val());
                //el.val(vst.html());
                $(st).empty().html('<img src="images/action_check.gif" title="Saved">').show(800);
                calcTotalDue();
            }
        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
            alert(textStatus);
        }
    });
    return true;
}

function saveOSComment(cid,msel,mtxt) {
    $.ajax({
        cache:false,
        type : 'POST',
        url : 'subs/ajax_onesheet_req.php',
        dataType : 'json',
        data: {
            call : 'saveOneSheetComment',
            cid : cid,
            cmntflg : msel,
            cmnt : mtxt,
            optype : 'json'
        },
        success : function(data){
            if (parseInt(data)!=0) {
               getOneSheetComments(cid,$('#OneSheetComments'));
            }
            else {
               alert('ERROR:\nYour Comment did not save properly.\nContact Support if this error persists.');
            }
        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
            alert(textStatus);
        }
    });
    return true;
}

function OneSheetCommentDialog(cid,cpid,el) {    
    $('body').append('<div id="OneSheetCommentDialog"></div>');

    if (el.hasClass('complaint')) {
        var ctype='C';
    }
    else if (el.hasClass('service')) {
        var ctype='S';
    }
    
    var dialog = $("#OneSheetCommentDialog").dialog({
        dialogClass: 'noTitleDialog',
        modal: true,
        width: 250,
        height: 150,
        open: $("#OneSheetCommentDialog").html(OSCommentForm(cpid,ctype)),
        close: closeOSCommentDialog(),
        position: {
            my: 'left top',
            at: 'right bottom',
            of: el
        },
        buttons: {
            'Submit' : function() {
                var msel=$('#dlgcmntflg').val();
                var mtxt=$('#dlgcmnt').val();
                
                if (mtxt.length > 0) {                
                    saveOSComment(cid,msel,mtxt);
                    closeOSCommentDialog();
                }
                else {
                    alert('No Comment Text');
                }
            },
            'Cancel' : function() {
               closeOSCommentDialog();
            }
        }
    }).dialog("open");
}

function closeOSCommentDialog() {
    $("#OneSheetCommentDialog").dialog("close").remove();
}

function OSCommentForm(cpid,ctype) {
    out='';
    out+='Make a Selection, add a Comment, Click Submit';
    out+='<select id="dlgcmntflg">';
    
    if (ctype=='C' || ctype=='S') {
        out+='  <option value="'+ctype+'R:'+cpid+'">Resolve: '+cpid+'</option>';
        out+='  <option value="'+ctype+'F:'+cpid+'">Followup: '+cpid+'</option>';
    }
    else {
        out+='  <option value="0">Select...</option>';
        out+='  <option value="C:0">Lead Comment</option>';
        out+='  <option value="CC:0">Construction Comment</option>';
        out+='  <option value="S:1">Service Ticket</option>';
        out+='  <option value="C:1">Complaint</option>';
    }
    
    out+='</select><br/>';
    out+='<textarea id="dlgcmnt" cols="25"></textarea>';
    return out;
}

function isDate(txtDate)
{
  var currVal = txtDate;
  if(currVal == '')
    return false;
  
  //Declare Regex  
  var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/; 
  var dtArray = currVal.match(rxDatePattern); // is format OK?

  if (dtArray == null)
     return false;
 
  //Checks for mm/dd/yyyy format.
  dtMonth = dtArray[1];
  dtDay= dtArray[3];
  dtYear = dtArray[5];

  if (dtMonth < 1 || dtMonth > 12)
      return false;
  else if (dtDay < 1 || dtDay> 31)
      return false;
  else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31)
      return false;
  else if (dtMonth == 2)
  {
     var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
     if (dtDay> 29 || (dtDay ==29 && !isleap))
          return false;
  }
  return true;
}

function CmntCntrlDisplay(cval) {
    if (cval=='A') {
        $('.cmnt_disp').show();
    }
    
    if (cval=='L') {
        $('.cmnt_disp').hide();
        $('.disp_leads').show();
    }
    
    if (cval=='C') {
        $('.cmnt_disp').hide();
        $('.disp_Construction').show();
    }
    
    if (cval=='S') {
        $('.cmnt_disp').hide();
        $('.disp_Resolved').show();
        $('.disp_Service').show();
        $('.disp_Complaint').show();
    }
    
    if (cval=='R') {
        $('.cmnt_disp').hide();
        $('.disp_cresp').show();
    }
}

function saveOneSheetComment(cid,el)
{
    var ac=$('#mtext').val();
    var cf=$('#cmntflag').val();
    
    if (!isNaN(cid) && ac.length > 0) {
        $.ajax({
            cache:false,
            type : 'POST',
            url : 'subs/ajax_onesheet_req.php',
            dataType : 'json',
            data: {
                call : 'saveOneSheetComment',
                sysCID : cid,
                cmnt : ac,
                cmntflag : cf,
                optype : 'json'
            },
            success : function(data) {
                if (parseInt(data)!=0) {
                   $('#mtext').val('');
                   getOneSheetCommentList(cid,el);
                }
                else {
                   alert('ERROR:\nYour Comment did not save properly.\nContact Support if this error persists.');
                }
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
                alert(textStatus);
            }
        });
       return true;
    }
    else {
       alert('Type not Select or No Comment Text');
       return false;
    }
}

function getOneSheetComments(cid,el) {
    el.empty().html('<img src="images/mozilla_blu.gif">').show(500);
    $.ajax({
        cache:false,
        type : 'GET',
        url : 'subs/ajax_onesheet_req.php',
        dataType : 'html',
        data: {
            call : 'getOneSheetComments',
            cid : cid,
            optype : 'table'
        },
        success : function(data){
            el.empty().html(data).show(500);
            $('.setpointer').live('hover',function() {
                    $(this).css('cursor','pointer');
                }, function() {
                    $(this).css('cursor','auto');
                return false;
            });
        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
            alert('LC '+ textStatus);
        }
    });
    
    return true;
}

function getConstructionDates(jid) {
    var el=$('#osCDDisplayWrap');
    el.parent().show();
    el.empty().html('<img src="images/mozilla_blu.gif">').show(500);
    $.ajax({
        cache:false,
        type : 'GET',
        url : 'subs/ajax_onesheet_req.php',
        dataType : 'html',
        data: {
            call : 'getConstructionDates',
            jid : jid,
            optype : 'table'
        },
        success : function(data){
            el.empty().html(data);
            
            $('.datepick').click(function(){
                $(this).datepicker({
                    onClose: function(date){updateConDate(jid,date,$(this))}
                }).datepicker('show');
            });
            
            $('.datepick_dd').click(function(){
                $(this).datepicker({
                    onClose: function(date){updateDigDate(jid,date,$(this))}
                }).datepicker('show');
            });
            
            $('#editContractDate').click(function() {
                showeditContractDateDialog($(this));
            });
        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
            alert('LC '+ textStatus);
        }
    });
    
    return true;
}

function updateConDate(jid,date,el) {
    if (date.length) {
        var nm=el.attr('id');
        var sm=nm.split("_");
        var vm='#val_'+sm[1]+'_'+sm[2];
        var st='#status_'+sm[2];
        
        $('.clear_phase_status').empty();
        
        if (el.val()!=$(vm).html()) {
            $.ajax({
                cache:false,
                type : 'POST',
                url : 'subs/ajax_onesheet_req.php',
                dataType : 'json',
                data: {
                    call : 'saveConstructionDate',
                    jid : jid,
                    proc: nm,
                    pdate : date,
                    optype : 'json'
                },
                success : function(data){
                    if (data.error) {
                        el.val('');
                        alert('Error:\n\n'+data.result);
                    }
                    else {
                        $(st).empty().html('<img src="images/action_check.gif" title="Saved">').show(800);
                    }
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    alert('LC '+ textStatus);
                }
            });
        }
    }
}

function updateDigDate(jid,date,el) {
    if (date.length) {
        var vm=$('#val_DigDate');
        if (el.val()!=vm.html()) {
            //alert(date);
            $.ajax({
                cache:false,
                type : 'POST',
                url : 'subs/ajax_onesheet_req.php',
                dataType : 'json',
                data: {
                    call : 'updateDigDate',
                    jid : jid,
                    ddate : date,
                    optype : 'json'
                },
                success : function(data){
                    if (data=='' || data.error) {
                        el.val(vm.html());
                        alert('Error:\n\n'+data.result);
                    }
                    else {
                        //$(st).empty().html('<img src="images/action_check.gif" title="Saved">').show(800);
                        vm.html(el.val());
                        alert('Dig Date Saved');
                    }
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(textStatus);
                }
            });
        }
    }
}

function calcTotalRecv() {
    var tamt=0;
    
    $.each($('.CDCurrencyField'), function(k,v){
        var cfamt=parseFloat($(this).val());
        tamt=tamt+cfamt;
    });
    
    $.each($('.adj_line_amt'), function(k,v){
        var alamt=parseFloat($(this).html());
        tamt=tamt+alamt;
    });
    
    return tamt;
}

function calcTotalDue() {
    var conamt=parseFloat($('#ContractAmt').html());
    var phsamt=calcTotalRecv();
    var dueamt=conamt-phsamt;
    var frecvamt=(phsamt).toFixed(2);
    var fdueamt=(dueamt).toFixed(2);
    
    $('#total_recv').text(frecvamt);
    $('#total_due').text(fdueamt);
}

function parseEachRow(data) {
    var out='';
    $.each(data,function(k,v){
        out+=k+':'+v+'\n';
    });
    return out;
}

function closeeditContractDateDialog() {
    $("#editContractDateDialog").empty().dialog("close").remove();
}

function showeditContractDateDialog() {
    $("#editContractDateWorkSpace").empty().dialog("close").remove();
    $('body').append('<div id="editContractDateWorkSpace"></div>');
    
    $('#editContractDateWorkSpace').append('<input type="text" id="editContractDateActual"><br>');
    $('#editContractDateWorkSpace').css('text-align','center');
    $('#editContractDateActual').css('text-align','center');
    
    $('#editContractDateActual').val($('#editContractDate').html());
    $('#editContractDateActual').datepicker();
    
    var dialog = $("#editContractDateWorkSpace").dialog({
        dialogClass: 'noTitleDialog',
        modal: true,
        width: 200,
        close: $(this).dialog("close"),
        position: {
            my: 'middle top',
            at: 'middle bottom',
            of: $('#editContractDate')
        },
        buttons: {
            "Update": function() {
                submitEditContractDate();
            },
            "Cancel": function() {
                $(this).dialog("close");
            }
        }
    }).dialog("open");
}

function submitEditContractDate()
{
    var ContractDate = new Date($('#editContractDateActual').val());
    
    if (ContractDate)
    {
        $.ajax({
            cache : false,
            type : 'POST',
            url : jobscript,
            dataType : 'html',
            data : {
                call : 'job',
                subq : 'checkContractDate',
                usr_oid : acct_oid,
                usr_cid : acct_cid,
                usr_NewContractDate : $('#editContractDateActual').val(),
                optype : 'table'
            },
            success : function(data){
                if (parseInt(data)==0 || parseInt(data)==1) {
                    $('#editContractDate').text($('#editContractDateActual').val());
                    $('#editContractDateWorkSpace').dialog('close');
                    $('#editContractDateWorkSpace').remove();
                }
                else {
                    $('#editContractDateWorkSpace').append(data);
                }
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
                $('#editContractDateWorkSpace').append(textStatus);
            }
        });
    }
}