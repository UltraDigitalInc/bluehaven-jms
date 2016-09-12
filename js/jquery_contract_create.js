$(document).ready(function()
{
   //alert( $.browser.version );
   $('#createcontract_new').accordion({autoHeight:false});
   
   //$('#phsadd').click().alert('BOP');
   //var totalPS = totalPS + parseInt($('#full_sched').each('div#pay_sched'));
   
   //var tl=$('div.bboxbr').length();
   //alert($('#full_sched').each('div#pay_sched').length());
   //window.alert($('span.full_sched input.bboxbr').length);
   
   var setTPS =function()
               {
                  var ct_amt=parseFloat($('#ctramt').html());
                  var dp_amt=parseFloat($('#amt_501L').val());
                  var sp_amt=parseFloat($('#amt_531L').val());
                  
                  var tps = 0;
                  var ops = dp_amt + sp_amt;
                  //alert(ops);
                  
                  $('input.ps_phs_amt').each(function()
                     {
                        //alert($(this).val());
                        tps=tps + parseFloat($(this).val());
                     }
                  );
                  
                  //alert(ct_amt);   
                  //alert(tps);
                  
                  
                  
                  if ((ops+tps) != ct_amt)
                  {
                     alert('Pay Schedule Unbalanced: ' + (ct_amt-(ops+tps)));
                  }
                  else
                  {
                     //var ntps=
                     $('span.total_PS').append((ops+tps));
                  }
               }
   
   $('#CalcPS').click(setTPS);
   
   $('#phsadd').click(function()
   {
      if ($('#phssel').val() != 0)
      {
         var phsCode = $('#phssel').val();
         var phsText = $('#phssel :selected').text();
         
         var addPHS = '<span id="'+ phsCode + '"><label class=\"pay_sched\"><b>'+ phsText +'</b></label><div class="pay_sched"><input class="ps_phs_amt" id="per_a'+ phsCode + '" name=payschedule['+ phsCode +'][amt] type="text" value="0.00" size="7"> % <input id="per_p'+ phsCode +'" name=payschedule['+ phsCode +'][perc] type="hidden" value="0"><img class="del_item" src="images/delete.png"></div></span>';

         $('span.full_sched').append(addPHS);
      }
      else
      {
         alert('Select a Payment Schedule Option');
      }
   });

   $('.del_item')
      .click(function()
         {
            //alert($(this).parent().attr('id'));
            $(this)
            .parent()
            .parent()
            .remove()
            .unbind();
   });
      
   $('#delete_sched')
      .click(function()
         {
			$('.full_sched')
            .remove()
            .unbind();
   });
});