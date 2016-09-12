$(document).ready(function() {
   $('.leadCommentDialog').live('click',function(e){
      var el=$(this);
      displayLeadCommentDialog(el);
   });
   
   $('.setpointer').live('hover',function(e) {
         $(this).css('cursor','pointer');
      }, function() {
         $(this).css('cursor','auto');
      return false;
   });
   
   $('.texpandtext').live('click',function(e){
      $(this).hide();
      $(this).parent().children('span.thiddentext').show();
   });
   
   $('.adjustAppt').css('color','blue');
   $('.adjustCallb').css('color','blue');
   $('.cmntCnt').css('color','blue');
   
   function displayLeadCommentDialog(el)
   { 
      $("#jms_leadcomment_dialog").empty().dialog("close").remove();
      $('body').append('<div id="jms_leadcomment_dialog" style="display:none"></div>');
      
      var lnm=el.parent().parent().children('.allnames').children('.clname').html();
      
      var dialog = $("#jms_leadcomment_dialog").dialog({
         title : 'Lead Comments: '+ lnm,
         open : getLeadCommentList(el),
         close : closeLeadCommentDialog(),
         autoOpen: false,
         resizable: true,
         modal: true,
         width: 550,
         height: 300,
         position: {
             my: 'right top',
             at: 'left bottom',
             of: el
         }
      }).dialog("open");
      
      return false;
   }
   
   function closeLeadCommentDialog()
   {
      $("#jms_leadcomment_dialog").empty().dialog("close").remove();
   }
   
   function getLeadCommentList(el)
   {
      var cid=parseInt(el.parent().parent().children('.viewForms').children('.viewLeadForm').children('.sysCID').val());
      if (!isNaN(cid) && cid!=0)
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
               $('#jms_leadcomment_dialog').html(data);
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
               $('#jms_leadcomment_dialog').html(textStatus).show();
            }
        });
      }
   }
   
});