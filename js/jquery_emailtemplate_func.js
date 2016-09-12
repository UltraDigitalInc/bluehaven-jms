$(document).ready(function() {
   var EmailBody=$('#EmailBody');
   if (EmailBody.length > 0) {
      var isHTML=parseInt($('#isHTML').val());      
      if (isHTML==1) {
         //tinymce.get('EmailBody').getDoc().designMode = 'On';
      }
      else {
         //tinymce.get('EmailBody').getDoc().designMode = 'Off';
      }
   }
   
   $('#isHTML').change(function(){
      var isHTML=parseInt($('#isHTML').val());
      //alert(isHTML);
      if (isHTML==1) {
         alert('Warning!!\n\nAfter saving this Template, Email Body formatting will not be preserved.\n\nYou will need to reformat the Message Body to suit an HTML format.');
         //tinymce.get('EmailBody').getDoc().designMode = 'On';
      }
      else {
         alert('Warning!!\n\nAfter saving this Template, Email Body formatting will not be preserved.\n\nYou will need to reformat the Message Body to suit a Plain Text format.\n\nHTML elements will need to be removed manually.');
         //tinymce.get('EmailBody').getDoc().designMode = 'Off';
      }
   });
   
   //$('.dragtext').draggable();
   
   $('.CopytoTextArea').live('click',function(event){
      var e=$('#EmailBody');
      var t=e.val();
      var c=$(this).parent().children('.CopytoTextAreaContent').html();
      
      e.val(t + c);
   });
   
   $('.emailkeywords').live('click',function(e){
      e.preventDefault();
      var kt=$(this).children().text();
      //alert($(this).children().text());
      var eb=$('#EmailBody');
      var et=eb.val();
      
      eb.val(et + kt);
      //alert(et + kt);
   });
   
   $('#emailtemplatereview').live('click',function(event) {
      event.preventDefault();
      var etid=$('#etid').val();
      
      displayEmailSendDialog($(this));
   });
      
   function displayEmailSendDialog()
   { 
      $("#jms_emailsend_dialog").empty().dialog("close").remove();
      $('body').append('<div id="jms_emailsend_dialog" style="display:none"></div>');
      
      getEmailTemplate();
      
      var dialog = $("#jms_emailsend_dialog").dialog({
         title : 'Preview Email Template',
         close : closeEmailSendDialog(),
         autoOpen: false,
         resizable: true,
         modal: true,
         width: 640,
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
            sysCID : 0,
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
   
});