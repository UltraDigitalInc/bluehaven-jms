$(document).ready(function() {
   var sendingHTML = '<img src="images/mozilla_blu.gif"> Sending...';
   
   if ($('#LeadCommentList').length)
   {
      getLeadCommentList();
   }
   
   $('#refreshLeadComments').live('click',function(){
      getLeadCommentList();
   });
   
   $('#saveLeadComment').live('click',function(){
      saveLeadComment();
   });
   
   $('.setpointer').live('hover',function() {
         $(this).css('cursor','pointer');
      }, function() {
         $(this).css('cursor','auto');
      return false;
   });
   
   $('#NewCommentBlock').hide();
   $('#MarketingDataTable').hide();
   
   $('#expandLeadComments').live('click',function(){
      $('span.texpandtext').hide();
      $('span.thiddentext').show();
   });
   
   $('#ShowNewCommentBlock').click(function(){
     $('#NewCommentBlock').toggle();
     return false;
   });
   
   $('#showMarketingData').click(function(){
      $('#MarketingDataTable').toggle();
      return false;
   });
   
   $('#empreview').hover(function() {
         $(this).css('cursor','pointer');
      }, function() {
         $(this).css('cursor','auto');
      return false;
   });
   
   $('#empreviewNEW').live('click',function(event) {
      event.preventDefault();
      var etid=$('#etid').val();
      
      if (!isNaN(etid) && etid!=0)
      {
         displayEmailSendDialog($(this));
      }
      else
      {
         alert('Select an Email Template');
      }
   });
   
   $('.texpandtext').live('click',function(){
      $(this).hide();
      $(this).parent().children('span.thiddentext').show();
   });
   
   $('#submitleadupdate').click(function() {
      var str = 'The following field(s) are incomplete or incorrect:\n';
      var err = 0;
      
      var adm = $('#appt_mo').val();
      var add = $('#appt_da').val();
      var ady = $('#appt_yr').val();
      var adh = $('#appt_hr').val();
      var adn = $('#appt_mn').val();
      var hlm = $('#hold_mo').val();
      var hld = $('#hold_da').val();
      var hly = $('#hold_yr').val();
      
      if (adm != 0 || add != 0 || ady != 0 || adh != 0)
      {
         if (adm == 0)
         {
            str = str + 'Appointment Month Invalid\n';
            err++;
         }
         
         if (add == 0)
         {
            str = str + 'Appointment Day Invalid\n';
            err++;
         }
         
         if (ady == 0)
         {
            str = str + 'Appointment Year Invalid\n';
            err++;
         }
         
         if (adh == 0)
         {
            str = str + 'Appointment Hour Invalid\n';
            err++;
         }
      }
      
      if (hlm != 0 || hld != 0 || hly != 0)
      {
         if (hlm == 0)
         {
            str = str + 'Callback Month Invalid\n';
            err++;
         }
         
         if (hld == 0)
         {
            str = str + 'Callback Day Invalid\n';
            err++;
         }
         
         if (hly == 0)
         {
            str = str + 'Callback Year Invalid\n';
            err++;
         }
      }
      
      if (err > 0)
      {
         alert(str);
      }
      else
      {
         $('#UpdateLeadForm').submit();
      }
   });
   
   $('#submitleadupdateOLD').click(function(){
      $('#UpdateLeadForm').submit();
   });
   
   $('#closeEmailSendDialog').live('click',function(event){
      closeEmailSendDialog();
   });
   
   $('#processEmailTemplate').live('click',function(event){
      event.preventDefault();
      var nerr=false;
      
      if ($('#bmeEFile').length > 0) {
         nerr=($('#bmeEFile').val()==0)?true:false;
      }
      
      if (!nerr) {
         processEmailTemplate();
      }
      else {
         alert('You did not select a file.');
      }
   });
   
   $(".cfiles_attached").live('click',function(event) {
      var chid=$(this).attr('id');
      var tchid=chid.split('_');
      var nchid=parseInt(tchid[1]);
      
      if (!isNaN(nchid)) {
         $('body').append('<div id="filedlgdisp" style="display:none"></div>');
         //$('#filedlgdisp').empty().html(getFileInfo(nchid));
         
         var dialog = $('#filedlgdisp').dialog({
            open: getFileInfo(nchid),
            autoOpen: false,
            resizable: false,
            modal: true,
            position : {
               my: "left top",
               at: "right bottom",
               of: event,
               offset: "2 2"
            },
            width: 200,
            height: 150,
            buttons: {
               Close: function() {
                  $(this).dialog('close').remove();
               }
            }
         }).dialog("open");
   
         $(".ui-dialog-titlebar").hide();
      }
   });
   
   $('#closeFileDialog').live('click',function(){
      closeLFilesDialog();
   });
   
   function getFileInfo(nchid) {
      var cid=$('#sysCID').val();
      //var out;
      
      $.get('subs/ajax_leads_req.php?call=leads&subq=get_FileList&cid='+cid+'&chid='+nchid, function(data){
         //out=data;
         $('#filedlgdisp').empty().append(data);
      });      
      //return out;
   }
   
   function closeLFilesDialog() {
      $('#filedlgdisp').dialog("close").remove();
   }
   
   function processEmailTemplate()
   {
      var cid=$('#sysCID').val();
      var tid=$('#tmpetid').val();
      var efile=($('#bmeEFile').length > 0)?$('#bmeEFile').val():'';
      var vbme='';
      
      if ($('#bmeEmailBody').length > 0) {
         var bme=$('#bmeEmailBody').html();
         if (bme.length > 0) {
            var vbme=bme;
         }
      }
      
      $("#jms_emailsend_dialog").empty().html(sendingHTML).dialog("height","auto");
   
      $.ajax({
         cache:false,
         type : 'POST',
         url : 'subs/ajax_previewtemp.php',
         dataType : 'html',
         data: {
            call : 'processEmailTemplate',
            sysCID : cid,
            etid : tid,
            sbme: vbme,
            efile: efile
         },
         success : function(data){
            $("#jms_emailsend_dialog").empty().html(data).dialog("height","auto");
            getLeadCommentList();
         },
         error : function(XMLHttpRequest, textStatus, errorThrown) {
            $("#jms_emailsend_dialog").empty().html(textStatus).show();
         }
      });
   }
   
   function displayEmailSendDialog()
   { 
      $("#jms_emailsend_dialog").empty().dialog("close").remove();
      $('body').append('<div id="jms_emailsend_dialog" style="display:none"></div>');
      
      getEmailTemplate();
      
      var dialog = $("#jms_emailsend_dialog").dialog({
         title : 'Send Email Template',
         close : closeEmailSendDialog(),
         autoOpen: false,
         resizable: true,
         modal: true,
         width: 720,
         height: 480
      }).dialog("open");
      
      return false;
   }
   
   function getEmailTemplate()
   {
      $.ajax({
         cache:false,
         type : 'POST',
         url : 'subs/ajax_previewtemp.php',
         dataType : 'html',
         data: {
            sysCID : $('#sysCID').val(),
            etid : $('#etid').val()
         },
         success : function(data){
            $("#jms_emailsend_dialog").html(data);
         },
         error : function(XMLHttpRequest, textStatus, errorThrown) {
            $("#jms_emailsend_dialog").html(textStatus).show();
         }
      });
   }
   
   function closeEmailSendDialog()
   {
      $("#jms_emailsend_dialog").empty().dialog("close").remove();
   }
   
   function saveLeadComment()
   {
      var cid=parseInt($('#sysCID').val());
      var ac=$('#addcomment').val();
      
      if ((!isNaN(cid)) && ac.length > 0)
      {
         //alert(rn);
         $.ajax({
            cache:false,
            type : 'POST',
            url : 'subs/ajax_leads_req.php',
            dataType : 'json',
            data: {
                call : 'leads',
                subq : 'save_LeadComment',
                sysCID : cid,
                cmnt : ac,
                cmntflag: 0,
                optype : 'json'
            },
            success : function(data){
               if (parseInt(data)!=0)
               {
                  $('#addcomment').val('');
                  getLeadCommentList();
               }
               else
               {
                  alert('ERROR:\nYour Comment did not save properly.\nContact Support if this error persists.');
               }
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
               $('#LeadCommentList').html(textStatus).show();
            }
         });
         return true;
      }
      else
      {
         alert('No Comment Text or other error occurred');
         return false;
      }
   }
   
   function getLeadCommentList()
   {
      var cid=parseInt($('#sysCID').val());
      if (cid!=0)
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
               $('#LeadCommentList').html(data);
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
               $('#LeadCommentList').html(textStatus).show();
            }
        });
      }
   }
   
});