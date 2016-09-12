$(document).ready(function()
{
   //alert(window.opener);
   //alert(window.parent);
   
   var OrigBCamt = parseFloat($('#OrigBCAmt').html()) || 0;
   var OrigTotalAmt = parseFloat($('#OrigTotalAmt').html());
   var TotalAmtDisplay = parseFloat($('#TotalAmtDisplay').html());
      
   $('#BidAddDialog').hide();
   
   $('#PBAdjustDialog').hide();
   
   $('#BaseCommAdjustDialog').hide();
   
   $('#origbaseamt').hide();
   $('#OrigBCAmt').hide();
   $('#OrigTotalAmt').hide();
   
   $('#OpenBidAddDialog').click(function() {
      $('#BidAddDialog').dialog('open');
   });
   
   $('#BidAddDialog').dialog({
			bgiframe: true,
			autoOpen: false,
            resizeable: false,
			height: 300,
            width: 400,
			modal: true,
            buttons: {
               Close: function() {
				$(this).dialog('close');
			   }
            }
   });
   
   $('#OpenPBAdjustDialog').click(function() {
      $('#PBAdjustDialog').dialog('open');
   });
   
   $("#PBAdjustDialog").dialog({
      bgiframe: true,
      autoOpen: false,
      resizeable: false,
      draggable: false,
      height: 250,
      width: 300,
      modal: true,
      buttons: {
         Submit: function() {
            if ($('#PBadjamt').val()!='0.00')
            {
               $('#SubmitPBAdjust').submit();
               $(this).dialog('close');
            }
            else
            {
               alert('Amount invalid');
            }
         },
         Close: function() {
            $(this).dialog('close');
         }
      }
   });
   
   $('#OpenBaseCommAdjustDialog').click(function() {
      $('#BaseCommAdjustDialog').dialog('open');
      $('#baserate').html(parseInt($('#BCrwdrate').val() * 100));
      $('#basecommadj').html(parseFloat((parseFloat($('#BCrwdrate').val()) * parseFloat($('#baseamt').html()))));
      $('#basecommadj').formatCurrency({symbol:'',groupDigits:false});
      //$('#basecommadj');
   });
   
   $("#BaseCommAdjustDialog").dialog({
      bgiframe: true,
      autoOpen: false,
      resizeable: false,
      draggable: false,
      height: 250,
      width: 250,
      modal: true,
      buttons: {
         Apply: function() {
            if ($('#baserate').html() > 0)
            {
               //var adjTotal=parseFloat(parseFloat($('#BCrwdamt').val() - OrigBCamt));
               
               $('#BCrwdrate').val(parseFloat(parseFloat($('#baserate').html()) * .01));
               $('#BCrwdamt').val(parseFloat($('#basecommadj').html()));

               $('#BCratedisplay').html($('#baserate').html());
               $('#BCamtdisplay').html($('#basecommadj').html());
               
               //var adjTotal = parseFloat(parseFloat($('#BCrwdamt').val()) - parseFloat($('#BCamtdisplay').html())) || 0;
               
               $('#TotalAmtDisplay').html(
                                          parseFloat(
                                                     //parseFloat($('#BCrwdamt').val()) +
                                                     //parseFloat($('#OUrwdamt').val())
                                                     OrigTotalAmt +
                                                     parseFloat(parseFloat($('#BCrwdamt').val()) - OrigBCamt)
                                                     
                                          )
                                       ).formatCurrency({symbol:'',groupDigits:false});
               
               //alert(parseFloat((parseFloat($('#baserate').html() * .01)) * parseFloat($('#baseamt').html())));
               //alert(OrigBCamt);
               //alert(adjTotal);
               //alert(TotalAmtDisplay);
               //alert(parseFloat(parseFloat($('#BCrwdamt').val()) - OrigBCamt));

               $(this).dialog('close');
            }
            else
            {
               alert('Commission Percentage Invalid');
            }
         },
         Inc: function() {
            $('#baserate').html((parseInt($('#baserate').html()) + 1));            
            $('#basecommadj').html(parseFloat(((parseFloat($('#baserate').html() * .01)) * parseFloat($('#baseamt').html()))));
            $('#basecommadj').formatCurrency({symbol:'',groupDigits:false});
         },
         Dec: function() {
            $('#baserate').html((parseInt($('#baserate').html()) - 1));            
            $('#basecommadj').html(parseFloat(((parseFloat($('#baserate').html() * .01)) * parseFloat($('#baseamt').html()))));
            $('#basecommadj').formatCurrency({symbol:'',groupDigits:false});
         },
         Reset: function() {
            $('#baserate').html(parseInt($('#BCrwdrateorig').val() * 100));
            $('#basecommadj').html(parseFloat((parseFloat($('#BCrwdrateorig').val()) * parseFloat($('#baseamt').html()))));
            $('#basecommadj').formatCurrency({symbol:'',groupDigits:false});
         },
         Cancel: function() {
            $(this).dialog('close');
         }
      }
   });
   
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
   
   //$('#pbaccordion').show().accordion({collapsible:true,autoHeight:false});
   
   $('#SubmitEstUpdate').click(
        function()
        {
            $('#updateest').submit();
        }
   );
   
   $('.LockedEst').click(function() {
      alert('Contract Created. This Estimate cannot be modifed.');
      return true;
   });
   
   $('#SubmitAdjRetailPrice').click(
        function()
        {
            if ($('#c_amt').val()!='')
            {
               $('#AdjRetailPrice').submit();
            }
            else
            {
               alert('Invalid Retail Price');
            }
        }
   );
});