$(function(){
   $('#d1').datepicker();
   $('#d2').datepicker();
   $('#d3').datepicker();
   $('#d4').datepicker();
   $('#d5').datepicker();
   $('#d6').datepicker();
   $('#d7').datepicker();
   $('#d8').datepicker();
   
   $('#searchaccordion').accordion({
      autoHeight:true,
      active: 3
   });
   
   $('#searchtabs').tabs();
   
   $('#vendortabs').tabs();
   
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
      bgiframe: true,
      height: 200,
      modal: false
   });
   
   $('#addcomment').click(function() {
	  $('#leadcomment').dialog('open');
   })

   $('#alphanumlist').click(function() {
			$("#alphanumselect").dialog({
               draggable: false,
               resizable:false,
               bgiframe: true,
               width: 600,
               modal:true
            });
		})
});