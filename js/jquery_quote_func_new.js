$(document).ready(function()
{
   $('#EstRetail').tabs();
   
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
   
   $('#SubmitEstUpdate').click(function()
   {
      $('#updateest').submit();
   });
   
   $('#SubmitAdjRetailPrice').click(function()
   {
      if ($('#c_amt').val()!='')
      {
         $('#AdjRetailPrice').submit();
      }
      else
      {
         alert('Invalid Retail Price');
      }
   });
});