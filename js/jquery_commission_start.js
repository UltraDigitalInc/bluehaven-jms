window.onload = function()
{
   $(document).ready(function()
   {
      $("#overridetext").dialog({
         autoOpen: true,
         resizeable: false,
         draggable: false,
         bgiframe: true,
         height: 250,
         width: 400,
         modal: true,
         buttons: {
                   Close: function() {
                       $(this).dialog('close');
                   }
         }
      });
         
   });
}