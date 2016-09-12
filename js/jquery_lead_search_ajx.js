$(document).ready(function()
{
   var ajxscript     = 'subs/ajax_leads_req.php';
   var menu_tab_id   = parseInt($.cookie("AP_CB_menu_tab")) || 0;
   var spinnerIMG    = '<em>Loading...</em>';
   
   //alert(AP_CB_menu_tab_id);
   
   $('#AP_CB_menu').tabs({
      cache:false,
      selected:menu_tab_id,
      spinner: spinnerIMG,
      show:function(event,ui){
         var show_tab_id = ui.index;
         $.cookie("AP_CB_menu_tab", show_tab_id);
         
         switch(show_tab_id)
         {
            case 0:
               $.ajax({
                  cache:false,
                  type : 'GET',
                  url : ajxscript,
                  dataType : 'html',
                  data: {
                     call : 'leads',
                     subq : 'get_AP_list',
                     optype : 'table'
                  },
                  success : function(data){
                     $('#ResultsAP').html(data).show(500);
                  },
                  error : function(XMLHttpRequest, textStatus, errorThrown) {
                     $('#ResultsAP').html(textStatus).show(500);
                  }
               });
            break;
         
            case 1:
               $.ajax({
                  cache:false,
                  type : 'GET',
                  url : ajxscript,
                  dataType : 'html',
                  data: {
                     call : 'leads',
                     subq : 'get_CB_list',
                     optype : 'table'
                  },
                  success : function(data){
                     $('#ResultsCB').html(data).show(500);
                  },
                  error : function(XMLHttpRequest, textStatus, errorThrown) {
                     $('#ResultsCB').html(textStatus).show(500);
                  }
               });
            break;
         
            case 2:
               $.ajax({
                  cache:false,
                  type : 'GET',
                  url : ajxscript,
                  dataType : 'html',
                  data: {
                     call : 'leads',
                     subq : 'get_ER_list',
                     optype : 'table'
                  },
                  success : function(data){
                     $('#ResultsER').html(data).show(500);
                  },
                  error : function(XMLHttpRequest, textStatus, errorThrown) {
                     $('#ResultsER').html(textStatus).show(500);
                  }
               });
            break;
         
            case 3:
               $.ajax({
                  cache:false,
                  type : 'GET',
                  url : ajxscript,
                  dataType : 'html',
                  data: {
                     call : 'leads',
                     subq : 'get_NM_list',
                     optype : 'table'
                  },
                  success : function(data){
                     $('#ResultsNM').html(data).show(500);
                  },
                  error : function(XMLHttpRequest, textStatus, errorThrown) {
                     $('#ResultsNM').html(textStatus).show(500);
                  }
               });
            break;
         }
      }
   });
});