$(document).ready(function()
{
   var AP_CB_menu_tab_id   = parseInt($.cookie("AP_CB_menu_tab")) || 0;
   
   $('#AP_CB_menu').tabs({
      selected: AP_CB_menu_tab_id,
      show:function(event,ui){
                  var tab_id1 = ui.index;
                  $.cookie("AP_CB_menu_tab", tab_id1);
            } 
  });
});