$(document).ready(function() {
   var OrigBCamt = parseFloat($('#OrigBCAmt').html()) || 0;
   var OrigTotalAmt = parseFloat($('#OrigTotalAmt').html());
   var TotalAmtDisplay = parseFloat($('#TotalAmtDisplay').html());
   var mca = 0;
   var mincomtxt ='';
   
   //alert(calcCSamt());
   
   $('#BidAddDialog').hide();
   
   $('#PBAdjustDialog').hide();
   
   $('#BaseCommAdjustDialog').hide();
   
   $('#MCODialog').hide();
   
   if ($('#MCOdisp').length > 0) {
      $('#OpenMCODialog').hide();
   }
   
   $('#origbaseamt').hide();
   $('#OrigBCAmt').hide();
   $('#OrigTotalAmt').hide();
   
   $('#TotalAmtDisplay').html(parseFloat(calcCSamt()));
   $('#TotalAmtDisplay').formatCurrency({symbol:'',groupDigits:false});
   
   $('.setpointer').live('hover',function() {
         $(this).css('cursor','pointer');
      },
      function() {
         $(this).css('cursor','auto');
      return false;
   });
   
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
         position: {
            my: 'top right',
            at: 'left bottom',
            of: $('#OpenPBAdjustDialog')
         },
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
         position: {
            my: 'top right',
            at: 'left bottom',
            of: $('#OpenBaseCommAdjustDialog')
         },
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
                  
                  $('#TotalAmtDisplay').html(parseFloat(OrigTotalAmt + parseFloat(parseFloat($('#BCrwdamt').val()) - OrigBCamt))).formatCurrency({symbol:'',groupDigits:false});
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
         dialogClass: 'noTitleDialog',
         bgiframe: true,
         modal: true,
         draggable: false,
         autoOpen: false,
         height: 275,
         width: 200,
         resizable: false,
         position: {
            my: 'left bottom',
            at: 'right top',
            of: $('#OpenMCODialog')
         },
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
            Cancel: function() {
               CancelMCODialog();
            }
         }
      })
      .dialog('open');
   }
   
   $('#OpenMCODialog').click(function(event) {
      //event.preventDefault();
      
      $('body').append('<div id="MCODialog"></div>');
      $('#MCODialog').append('<div id="MCOData"></div>');
      
      MCODialog();
   
      $('#MCOData').append('<table><tr><td>Manual Commission Override</td></tr><tr><td valign="top" id="MCOwrkcell"><div id="MCOhdrwrkspc"><label class="lblMCOwrkspc">Type:</label><br><select class="MCOval" id="MCOcatid"><option value="65535" SELECTED>Select...</option><option value="10">Fixed Amount</option><option value="11">Percent</option></select></div></td></tr><tr><td valign="top" id="MCOnoteswrkspc"></td></tr></table>');
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
      var cbtype  =parseInt($('select#MCOcatid').val());
      var estid=parseInt($('#qestid').val());
      
      if ($('#mincommAdj').length > 0) {
         mca = parseFloat($('#mincommAdj').html());
         mincomtxt=holdminComm();         
         $('.mincomm').remove();
      }
      
      if (cbtype==10) {
         if ($('#MCOdisp').length == 0 && parseFloat($('input#MCOamt').val()) != 0 && !isNaN(parseFloat($('input#MCOamt').val()))) {
            var ctype=1;
            var label='MCF';
            var pamt=$('input#MCOamt').val();
            //var oamt=(parseFloat(tot) - mca) + parseFloat(pamt);
            var mcodl='<tr id="MCOdisp"><td class="wh"><img src="../images/pixel.gif"></td><td class="wh" align="right"><span class="JMStooltip" id="MCOtitledisp"><b>Manual Override</b></span></td><td class="wh" align="center"><span id="MCOQuanDisp"></span></td><td class="wh" align="center"><span id="MCOUnitDisp">fx</span></td><td class="wh"><img src="../images/pixel.gif"></td><td class="wh" align="right"><span id="MCOAmtDisp" class="csamt">'+pamt+'</span></td><td class="wh" align="center"><span class=\"JMStooltip noPrint\" id=\"MCOdel\" title=\"Delete Commission\"><a href="#"><img src="../images/action_delete.gif"></a></span></td></tr>';
            pushMCOData(estid,cbtype,ctype,label,pamt,0,mcodl);

            $('#OpenMCODialog').hide();
            $(this).dialog('close');
            ierr=0;
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
      
      if (cbtype==11) {
         if ($('#MCOdisp').length == 0 && $('div#MCOamt').length > 0 && !isNaN(parseFloat($('input#MCOperc').val()))) {
            var ctype=2;
            var label='MCP';
            var famt=$('div#MCOamt').html();
            var rwdrate=$('input#MCOperc').val();
            //var oamt=(parseFloat(tot) - mca) + parseFloat(famt);
            var mcodl='<tr id="MCOdisp"><td class="wh"><img src="../images/pixel.gif"></td><td class="wh" align="right"><span class="JMStooltip" id="MCOtitledisp"><b>Manual Override</b></span></td><td class="wh" align="center"><span id="MCOQuanDisp">'+rwdrate+'</span></td><td class="wh" align="center"><span id="MCOUnitDisp">%</span></td><td class="wh"><img src="../images/pixel.gif"></td><td class="wh" align="right"><span id="MCOAmtDisp" class="csamt">'+famt+'</span></td><td class="wh" align="center"><span class=\"JMStooltip noPrint\" id=\"MCOdel\" title=\"Delete Commission\"><a href="#"><img src="../images/action_delete.gif"></a></span></td></tr>';
            pushMCOData(estid,cbtype,ctype,label,famt,rwdrate,mcodl);
         
            $('#OpenMCODialog').hide();
            $(this).dialog('close');
            ierr=0;
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
   
   function pushMCOData(estid,cbtype,ctype,label,rwdamt,rwdrate,opt) {
      var MCOnotes=$('#MCOnotes').val();
      
      if (MCOnotes.length > 0) {
         $('#MCOtitledisp').attr('title',MCOnotes);
      }
      
      if (!isNaN(estid) && estid!=0 && (cbtype==10 || cbtype==11)) {
         $.ajax({
            cache:false,
            type : 'POST',
            url : 'subs/ajax_estimate_req.php',
            dataType : 'json',
            data: {
               call : 'saveManualCommissionAdjust',
               estid : estid,
               cbtype : cbtype,
               ctype : ctype,
               rwdamt : rwdamt,
               rwdrate : rwdrate,
               label : label,
               notes : MCOnotes,
               optype : 'json'
            },
            success : function(data){
               if (!data.error) {
                  var op=data.result;

                  $('#csched_total_line').before(opt);
                  $('#TotalAmtDisplay').html(parseFloat(calcCSamt()));
                  $('#TotalAmtDisplay').formatCurrency({symbol:'',groupDigits:false});
                  
                  pushMCOtoForm(cbtype,ctype,0,rwdamt,rwdrate,label,MCOnotes);
               }
               else {
                  alert('Error: ' + data.result);
               }
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
               alert('Error Out: '+ textStatus);
            }
         });
      }
      else {
         alert('Error saving Adjust');
      }
   }
   
   function pushMCOtoForm(cmid,ctype,secid,rwdamt,rwdrate,label,notes) {
      var fel=$('#frmCreateContract');
      
      fel.append('<input type="hidden" class="cschedPush" name="csched['+ cmid.toString() +'][cmid]" value="'+ cmid.toString() +'">');
      fel.append('<input type="hidden" class="cschedPush" name="csched['+ cmid.toString() +'][rwdamt]" value="'+ rwdamt.toString() +'">');
      fel.append('<input type="hidden" class="cschedPush" name="csched['+ cmid.toString() +'][secid]" value="'+ secid.toString() +'">');
      fel.append('<input type="hidden" class="cschedPush" name="csched['+ cmid.toString() +'][catid]" value="'+ cmid.toString() +'">');
      fel.append('<input type="hidden" class="cschedPush" name="csched['+ cmid.toString() +'][ctype]" value="'+ ctype.toString() +'">');
      fel.append('<input type="hidden" class="cschedPush" name="csched['+ cmid.toString() +'][rwdrate]" value="'+ rwdrate.toString() +'">');
      fel.append('<input type="hidden" class="cschedPush" name="csched['+ cmid.toString() +'][trgwght]" value="0">');
      fel.append('<input type="hidden" class="cschedPush" name="csched['+ cmid.toString() +'][d1]" value="0">');
      fel.append('<input type="hidden" class="cschedPush" name="csched['+ cmid.toString() +'][d2]" value="0">');
      fel.append('<input type="hidden" class="cschedPush" name="csched['+ cmid.toString() +'][label]" value="'+ label.toString() +'">');
      fel.append('<input type="hidden" class="cschedPush" name="csched['+ cmid.toString() +'][notes]" value="'+ notes.toString() +'">');
      return true;
   }
   
   $('#MCOdel').live('click',function(event) {
      event.preventDefault();
      
      if (mca!=0) {
         $('#csched_total_line').before(mincomtxt);
      }
      
      deleteMCOData();
   });
   
   function calcCSamt() {
      var out=0;
      
      $.each($('.csamt'),function(k,v){
         var camt=parseFloat($(this).html());
         out=out+camt;
      });
      
      return out;
   }
   
   function deleteMCOData() {
      var estid=$('#qestid').val();
      //var csid=
      //var mamt=$('#MCOAmtDisp').html();
      //var tamt=$('#TotalAmtDisplay').html();
      //var oamt=(parseFloat(tamt) + mca) - parseFloat(mamt);
      //var oamt=parseFloat(calcCSamt());
      
      if (!isNaN(estid) && estid!=0) {
         $.ajax({
            cache:false,
            type : 'POST',
            url : 'subs/ajax_estimate_req.php',
            dataType : 'json',
            data: {
               call : 'deleteManualCommissionAdjust',
               estid : estid,
               optype : 'json'
            },
            success : function(data) {
               if (!data.error) {
                  var op=data.result;
                  //alert(op);                  
                  $('.cschedPush').remove();
                  $('#MCOdisp').remove();
                  //$('#OpenMCODialog').show();
                  $('#TotalAmtDisplay').html(parseFloat(calcCSamt()));
                  $('#TotalAmtDisplay').formatCurrency({symbol:'',groupDigits:false});
                  
                  if ($('#MCOdisp').length == 0) {
                     $('#OpenMCODialog').show();
                  }
               }
               else {
                  alert('Error: ' + data.result);
               }
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
               alert('Error Out: '+ textStatus);
            }
         });
      }
      else {
         alert('Error saving Adjust');
      }
   }
   
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
});