$(document).ready(function()
{
   var OrigBCamt = parseFloat($('#OrigBCAmt').html()) || 0;
   var OrigTotalAmt = parseFloat($('#OrigTotalAmt').html());
   var TotalAmtDisplay = parseFloat($('#TotalAmtDisplay').html());
   var mca = 0;
   var mincomtxt ='';
      
   $('#BidAddDialog').hide();
   
   $('#PBAdjustDialog').hide();
   
   $('#BaseCommAdjustDialog').hide();
   
   $('#MCODialog').hide();
   
   $('#origbaseamt').hide();
   $('#OrigBCAmt').hide();
   $('#OrigTotalAmt').hide();
   
   function BidAddDialog() {
      var dialog = 
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
      }).dialog('open');
   }
   
   $('#OpenBidAddDialog').click(function() {
      BidAddDialog();
   });
   
   function PBAdjDialog() {
      var dialog =
      $("#PBAdjustDialog").dialog({
         bgiframe: true,
         autoOpen: false,
         resizeable: false,
         draggable: false,
         height: 250,
         width: 400,
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
            Cancel: function() {
               $(this).dialog('close');
            }
         }
      }).dialog('open');
   }
   
   $('#OpenPBAdjustDialog').click(function() {
      PBAdjDialog();
   });
   
   function BaseCommAdjDialog() {
      var dialog =
      $("#BaseCommAdjustDialog").dialog({
         title: "Base Commission Adjust",
         bgiframe: true,
         modal: true,
         autoOpen: false,
         draggable: false,
         height: 250,
         width: 450,
         resizeable: false,
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
                  $(this).dialog('close');
               }
               else
               {
                  alert('Commission Percentage Invalid');
               }
            },
            Increase: function() {
               $('#baserate').html((parseInt($('#baserate').html()) + 1));            
               $('#basecommadj').html(parseFloat(((parseFloat($('#baserate').html() * .01)) * parseFloat($('#baseamt').html()))));
               $('#basecommadj').formatCurrency({symbol:'',groupDigits:false});
            },
            Decrease: function() {
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
      }).dialog('open');
   }
   
   $('#OpenBaseCommAdjustDialog').click(function() {
      
      BaseCommAdjDialog();
      
      $('#baserate').html(parseInt($('#BCrwdrate').val() * 100));
      $('#basecommadj').html(parseFloat((parseFloat($('#BCrwdrate').val()) * parseFloat($('#baseamt').html()))));
      $('#basecommadj').formatCurrency({symbol:'',groupDigits:false});
   });
   
   function MCODialog() {
      var dialog =
      $('#MCODialog').dialog({
         title: "Manual Override Commission",
         bgiframe: true,
         modal: true,
         draggable: false,
         autoOpen: false,
         height: 300,
         width: 350,
         resizable: false,
         close: function() {
            CancelMCODialog();
         },
         buttons: {
            Apply: function() {
               var cat=parseInt($('select#MCOcatid').val());
               
               if (cat==11) {
                  CalcMCOWrkspc();
               }
               
               ApplyMCOValue();
            },
            Reset: function() {
               ResetMCOWrkspc();
            },
            Cancel: function() {
               CancelMCODialog();
            }
         }
      })
      .dialog('open');
   }
   
   $('#OpenMCODialog').click(function(event) {
      event.preventDefault();
      
      $('body').append('<div id="MCODialog"></div>');
      $('#MCODialog').append('<div id="MCOData"></div>');
      
      MCODialog();
      
      //$('#MCOData').append('<div id="MCOhdrwrkspc"><label class="lblMCOwrkspc">Type:</label> <select class="MCOval" id="MCOcatid"><option value="65535" SELECTED>Select...</option><option value="10">Fixed Amount</option><option value="11">Percent</option></select></div>');
      $('#MCOData').append('<table><tr><td valign="top" id="MCOwrkcell"><div id="MCOhdrwrkspc"><label class="lblMCOwrkspc">Type:</label><br><select class="MCOval" id="MCOcatid"><option value="65535" SELECTED>Select...</option><option value="10">Fixed Amount</option><option value="11">Percent</option></select></div></td></tr><tr><td valign="top" id="MCOnoteswrkspc"></td></tr></table>');
      $('select#MCOcatid').val(65535);
   });
   
   $('select#MCOcatid').live('change',function() {
      var cat=parseInt($('select#MCOcatid').val());
      
      $('div#MCOfixwrkspc').remove();
      $('div#MCOprcwrkspc').remove();
      $('div#MCOwrkspc').remove();
      $('#MCOnoteswrkspc').empty();
      
      if (cat==10) {
         $('#MCOwrkcell').append('<div id="MCOfixwrkspc"><label class="lblMCOwrkspc">Amount:</label><br><input class="MCOval" type="text" id="MCOamt" value="0.00"></div>');
         $('#MCOwrkcell').append('<div id="MCOwrkspc"></div>');
         $('#MCOnoteswrkspc').append('Notes:<br><textarea id="MCOnotes"></textarea>');
      }
      
      if (cat==11) {
         $('#MCOwrkcell').append('<div id="MCOprcwrkspc"><label class="lblMCOwrkspc">Source:</label><br><select class="MCOval" id="MCOcalcsrc"><option value="ppb" SELECTED>Price per Book</option><option value="apb">Adjusted Price per Book</option><option value="rcp">Retail Contract Amount</option><option value="oub">O/U Book</option></select><p><label class="lblMCOwrkspc">Percent:</label><br><input class="MCOval" type="text" id="MCOperc" value="0"><p></div>');
         $('#MCOwrkcell').append('<div id="MCOwrkspc"></div>');
         $('#MCOnoteswrkspc').append('Notes:<br><textarea id="MCOnotes"></textarea>');
      }
   });
   
   function holdminComm() {
      var trb='<tr class="mincomm">';
      var tre='</tr>';
      var out='';
      
      $('.mincomm').each(function(){
         out+=trb + $(this).html() + tre;
      });
   
      return out;
   }
   
   function ApplyMCOValue() {
      var ierr=0;
      var ierrtxt='';
      var tot  =parseFloat($('#TotalAmtDisplay').html());
      var cat  =parseInt($('select#MCOcatid').val());
      var mcodl='<tr id="MCOdisp"><td class="wh"><img src="../images/pixel.gif"></td><td class="wh" align="right"><span class="JMStooltip" id="MCOtitledisp"><b>Manual Override</b></span></td><td class="wh" align="center"><span id="MCOQuanDisp"></span></td><td class="wh" align="center"><span id="MCOUnitDisp"></span></td><td class="wh"><img src="../images/pixel.gif"></td><td class="wh" align="right"><span id="MCOAmtDisp"></span></td><td class="wh" align="center"><span class=\"JMStooltip noPrint\" id=\"MCOdel\" title=\"Delete Commission\"><a href="#"><img src="../images/action_delete.gif"></a></span></td></tr>';
      
      if ($('#mincommAdj').length > 0) {
         mca = parseFloat($('#mincommAdj').html());
         mincomtxt=holdminComm();         
         $('.mincomm').remove();
      }
      
      if (cat==10) {
         if ($('#MCOdisp').length == 0 && parseFloat($('input#MCOamt').val()) != 0 && !isNaN(parseFloat($('input#MCOamt').val()))) {
            var pamt=$('input#MCOamt').val();
            var oamt=(parseFloat(tot) - mca) + parseFloat(pamt);
            
            $('#csched_total_line').before(mcodl);
            $('#MCOUnitDisp').html('fx');
            $('#MCOAmtDisp').html(pamt);
            $('#TotalAmtDisplay').html(oamt);
            $('#TotalAmtDisplay').formatCurrency({symbol:'',groupDigits:false});
            
            pushMCOData(cat,26,pamt,0,0,'frmCreateContract');
            
            ierr=0;
            $('#OpenMCODialog').hide();
            $(this).dialog('close');
         }
         else {
            if ($('#MCOdisp').length > 0) {
               ierr++;
               ierrtxt='Manual Override Commission already exists\n';
            }
            
            if (parseFloat($('input#MCOamt').val()) == 0) {
               ierr++;
               ierrtxt='Fixed Amount is zero';
            }
            
            if (isNaN(parseFloat($('input#MCOamt').val()))) {
               ierr++;
               ierrtxt='Fixed Amount is not a valid number';
            }
         }
      }
      
      if (cat==11) {
         if ($('#MCOdisp').length == 0 && $('div#MCOamt').length > 0 && !isNaN(parseFloat($('input#MCOperc').val()))) {
            var famt=$('div#MCOamt').html();
            var rate=$('input#MCOperc').val();
            var oamt=(parseFloat(tot) - mca) + parseFloat(famt);
            
            $('#csched_total_line').before(mcodl);
            $('#MCOQuanDisp').html(rate);
            $('#MCOUnitDisp').html('%');
            $('#MCOAmtDisp').html(famt);
            $('#TotalAmtDisplay').html(oamt);
            $('#TotalAmtDisplay').formatCurrency({symbol:'',groupDigits:false});
            
            pushMCOData(cat,26,famt,rate,0,'frmCreateContract');
            
            ierr=0;
            $('#OpenMCODialog').hide();
            $(this).dialog('close');
         }
         else {
            if ($('#MCOdisp').length > 0) {
               ierr++;
               ierrtxt='Manual Override Commission already exists\n';
            }
            
            if ($('div#MCOamt').length > 0 == 0) {
               ierr++;
               ierrtxt='Percentage has not been Calculated';
            }
            
            if (isNaN(parseFloat($('input#MCOperc').val()))) {
               ierr++;
               ierrtxt='Percentage is not a valid number';
            }
         }
      }
      
      if (ierr==0) {
         $('#MCODialog').remove();
      }
      else {
         alert(ierrtxt);
      }
   }
   
   function ResetMCOWrkspc() {
      $('div#MCOfixwrkspc').remove();
      $('div#MCOprcwrkspc').remove();
      $('div#MCOwrkspc').remove();
      $('select#MCOcatid').val(65535);
      return;
   }
   
   function CancelMCODialog() {
      $(this).dialog('close');
      $('#MCODialog').remove();
      return;
   }
   
   function CalcMCOWrkspc() {
      var cat  =parseInt($('select#MCOcatid').val());
      
      if (cat==11) {
         var cpsrc=$('select#MCOcalcsrc').val();
         var cpprc=$('input#MCOperc').val();
         var cpout=calcCPprc(cpsrc,cpprc);
         
         if ($('div#MCOamt').length == 0)
         {
            $('div#MCOwrkspc').append('<label class="lblMCOwrkspc">Amount:</label> <div id="MCOamt"></div><p>');
         }

         $('div#MCOamt').html(cpout);
         $('div#MCOamt').formatCurrency({symbol:'',groupDigits:false});
      }
      else {
         alert('Calc only required for Percent operations');
      }
   }
   
   function pushMCOData(catid,secid,rwdamt,rwdrate,thresh,el) {
      var cmid=catid;
      var fel='#' + el;
      var MCOnotes=$('#MCOnotes').val();
      
      if (catid==10) {
         var ctype=1;
         var label='MCF';
      }
      
      if (catid==11) {
         var ctype=2;
         var label='MCP';
      }
      
      if (MCOnotes.length > 0) {
         $('#MCOtitledisp').attr('title',MCOnotes);
      }
      
      //alert(rwdamt);
      $(fel).append('<input type="hidden" class="cschedPush" name="csched['+ cmid.toString() +'][cmid]" value="'+ cmid.toString() +'">');
      $(fel).append('<input type="hidden" class="cschedPush" name="csched['+ cmid.toString() +'][rwdamt]" value="'+ rwdamt.toString() +'">');
      $(fel).append('<input type="hidden" class="cschedPush" name="csched['+ cmid.toString() +'][secid]" value="'+ secid.toString() +'">');
      $(fel).append('<input type="hidden" class="cschedPush" name="csched['+ cmid.toString() +'][catid]" value="'+ catid.toString() +'">');
      $(fel).append('<input type="hidden" class="cschedPush" name="csched['+ cmid.toString() +'][ctype]" value="'+ ctype.toString() +'">');
      $(fel).append('<input type="hidden" class="cschedPush" name="csched['+ cmid.toString() +'][rwdrate]" value="'+ rwdrate.toString() +'">');
      $(fel).append('<input type="hidden" class="cschedPush" name="csched['+ cmid.toString() +'][trgwght]" value="'+ thresh.toString() +'">');
      $(fel).append('<input type="hidden" class="cschedPush" name="csched['+ cmid.toString() +'][d1]" value="0">');
      $(fel).append('<input type="hidden" class="cschedPush" name="csched['+ cmid.toString() +'][d2]" value="0">');
      $(fel).append('<input type="hidden" class="cschedPush" name="csched['+ cmid.toString() +'][label]" value="'+ label.toString() +'">');
      $(fel).append('<input type="hidden" class="cschedPush" name="csched['+ cmid.toString() +'][notes]" value="'+ MCOnotes.toString() +'">');
   }
   
   $('#MCOdel').live('click',function(event) {
      event.preventDefault();
      
      if (mca!=0)
      {
         $('#csched_total_line').before(mincomtxt);
         //alert('Prior Min Comm alert: '+ mca);
      }
      
      var mamt=$('#MCOAmtDisp').html();
      var tamt=$('#TotalAmtDisplay').html();
      var oamt=(parseFloat(tamt) + mca) - parseFloat(mamt);
      
      $('#TotalAmtDisplay').html(oamt);
      $('#TotalAmtDisplay').formatCurrency({symbol:'',groupDigits:false});
      
      
      $('.cschedPush').remove();
      $('#MCOdisp').remove();
      $('#OpenMCODialog').show();
   });
   
   function calcCPprc(src,pprc)
   {
      var out=0;
      
      if (pprc != 0)
      {
         var ppb=$('#ppbook').html();
         var apb=$('#apbook').html();
         var rcp=$('#c_amt').val();
         var oub=$('#oubook').html();
         var clc=0;
         var prc=(pprc * .01);
         
         switch (src)
         {
            case 'ppb':
               clc = ppb;
            break;
         
            case 'apb':
               clc = apb;
            break;
            
            case 'rcp':
               clc = rcp;
            break;
         
            case 'oub':
               clc = oub;
            break;
         }
         
         out = parseFloat(clc) * prc;
      }
   
      return out;
   }
   
   function mincommAdj_display()
   {
   }
   
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
   
   $('#SubmitEstUpdate').click(function(){
      $('#updateest').submit();
   });
   
   $('.LockedEst').click(function() {
      alert('Contract Created. This Estimate cannot be modifed.');
      return true;
   });
   
   $('#SubmitAdjRetailPrice').click(function(){
      if ($('#c_amt').val()!='')
      {
         $('#AdjRetailPrice').submit();
      }
      else
      {
         alert('Invalid Retail Price');
      }
   });
   
   $('#ViewEstCost').live('click',function(){
      var estcst=$('#TotalAmtDisplay').html();
      $('#ViewEstCost').append('<input type="hidden" name="TotalEstComm" value="'+ estcst +'">')
      
      return true;
   });
   
   /*
   $('#CreateContractButton').live('click',function(event){
      event.preventDefault();
      
      $('body').append('<div id="ContractCreateDialog"></div>');
      $('#ContractCreateDialog').append('<div id="ContractWorksheet"></div>');
      
      CreateContract();
      
   });
   
   function CreateContract()
   {
      var dialog =
      $("#ContractCreateDialog").dialog({
         title: 'Create Contract',
         bgiframe: true,
         autoOpen: false,
         resizeable: false,
         draggable: false,
         height: 300,
         width: 440,
         modal: true,
         buttons: {
            Submit: function() {
            },
            Cancel: function() {
               $(this).dialog('close');
               $('#ContractCreateDialog').remove();
            }
         }
      }).dialog('open');
   }
   
   function BuildContractWorksheet()
   {
      
   }
   */
   
});