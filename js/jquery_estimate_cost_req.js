$(document).ready(function()
{
   var show_warning  = 0;
   var eai_tab_id    = parseInt($.cookie("eai_view_menu_tab")) || 0;
   var aoid          = parseInt($('#active_oid').val()) || 0;
   var asid          = parseInt($('#active_sid').val()) || 0;
   var esid          = parseInt($('#active_estid').val()) || 0;
   var LoadStatus    = '<img src="../images/mozilla_blu.gif"> Loading, please wait...';
   var ajxscript     = 'ajx/ajax_estimate_EditItems.php';
   
   //$('#LoadStatus').hide();
   $('#LoadStatus').append(LoadStatus)
      .hide()  // hide it initially 
      .ajaxStart(function() { 
         $(this).show(); 
      }) 
      .ajaxStop(function() { 
        $(this).hide(); 
      }) 
   ;

   BuildCustomerEstimateInfo(aoid,esid);
   BuildAccordion();
   
   function BuildAccordion()
   {
      var ckbx= [1,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,33,34,35,36,37,37,41,42,43,45,46,47,69,70,72,77];
      var qnbx= [2,39,55,58];
      var RetailCategories_JSON =  (function(){
      var json = null;
         $.ajax({
            'async': false,
            'global': false,
            'url': ajxscript+'?call=get_RetailCategories&goid='+aoid+'&optype=json',
            'dataType': "json",
            'success': function (data) {
               json = data;
            }
         });
      
         return json;
      })();
      
      $('#PBAccordionContainer').append('<div id="PBaccordion"></div>');
      $('#PBaccordion').hide();
      
      $.each(RetailCategories_JSON,function(i,v){
         var tout='';
         var RetailItems_JSON =  (function(){
            var json = null;
            $.ajax({
               'async': false,
               'global': false,
               'url': ajxscript+'?call=get_RetailItems&goid='+aoid+'&catid='+v.catid+'&estid='+esid+'&optype=json',
               'dataType': "json",
               'success': function (data) {
                  json = data;
               }
            });
         
            return json;
         })();
         
         tout+='<h3><a href="#">'+v.catname+'</a></h3>';
         tout+='<div id="itemset_'+v.catid+'"><table class="inner_borders" border=1 width="100%">';
         
         $.each(RetailItems_JSON,function(ii,iv){
            if (iv.qtype==32)
            {
               tout+='<tr class="darkgray"><td colspan="4"><b>'+iv.ritem+'</b></td></tr>';
            }
            else
            {
               var est=iv.estinfo;
               
               tout+='  <input type="hidden" name="aaaa'+iv.rid+'" value=\"'+iv.rid+'\">'; //Item
               tout+='  <input type="hidden" name="fffa'+iv.rid+'" value=\"'+iv.qtype+'\">'; //Qtype Code
               tout+='  <input type="hidden" name="ggga'+iv.rid+'" value=\"'+iv.commtype+'\">'; //Commtype code
               tout+='  <input type="hidden" name="hhha'+iv.rid+'" value=\"'+iv.crate+'\">'; //CommRate
               tout+='  <input type="hidden" name="iiia'+iv.rid+'" value=\"'+iv.qcalc+'\">'; //Quan for Calc
               
               tout+='  <input type="hidden" name="ddda'+iv.rid+'" value=\"'+iv.rprice+'\">'; //Price
               //tout+='  <input type="hidden" name="bbba'+iv.rid+'" value=\"1\">'; //Quantity
               
               tout+='<tr class="even">';
               tout+='  <td align="left">';
               tout+=iv.ritem
               
               if (iv.qtype==33)
               {
                  var bid=iv.bidinfo;
                  
                  if (typeof bid.bidinfo == "undefined")
                  {
                     tout+='  <br><textarea name="eeea'+iv.rid+'" rows="2" cols="60"></textarea>';
                  }
                  else
                  {
                     tout+='  <br><textarea name="eeea'+iv.rid+'" rows="2" cols="60">'+bid.bidinfo+'</textarea>';
                  }
               }
               
               tout+='  </td>';
               tout+='  <td align="center">';
               
               if (iv.mabrv.length > 0)
               {
                  tout+=iv.mabrv
               }
               else
               {
                  tout+='     <img src="../images/pixel.gif">';
               }
               
               tout+='  </td>';
               tout+='  <td align="right" width="25px">';
               
               if (iv.qtype==33)
               {
                  if (typeof bid.bidinfo =='undefined')
                  {
                     tout+='     <input type="text" name="ddda'+iv.rid+'" size="5" maxlength="12" value="0">';
                  }
                  else
                  {
                     tout+='     <input type="text" name="ddda'+iv.rid+'" size="5" maxlength="12" value="'+est.rp+'">';
                  }
               }
               else
               {
                  if (iv.rprice != '0.00')
                  {
                     tout+=iv.rprice;
                  }
                  else
                  {
                     tout+='     <img src="../images/pixel.gif">';
                  }
               }

               tout+='  </td>';
               tout+='  <td align="center" width="50px">';
               
               if ($.inArray(iv.qtype,ckbx) > -1)
               {
                  if (parseInt(est.id) > 0)
                  {
                     tout+='     <input class="transnb" type="checkbox" name="bbba'+iv.rid+'" value="1" CHECKED>';
                  }
                  else
                  {
                     tout+='     <input class="transnb" type="checkbox" name="bbba'+iv.rid+'" value="1">';
                  }
               }
               else
               {
                  if ($.inArray(iv.qtype,qnbx) > -1)
                  {
                     if (parseInt(est.id) > 0)
                     {
                        tout+='     <input type="text" name="bbba'+iv.rid+'" size="2" maxlength="5" value="'+est.qn+'">';
                     }
                     else
                     {
                        tout+='     <input type="text" name="bbba'+iv.rid+'" size="2" maxlength="5" value="0">';
                     }
                  }
                  else
                  {
                     tout+=iv.qtype;
                  }
               }
               
               tout+='  </td>';
               tout+='</tr>';
            }
         });

         tout+='</table></div>';
         
         $('#PBaccordion').append(tout);
      });
      
      $('#rtlbrkdwnrtnbtn').append('<div class=\"noPrint\"><button id="returnRetailBreakdown">Retail Breakdown</button></div>');
      $('#updateitemsbtntop').append('<div class=\"noPrint\"><button id="UpdateEstItems1">Update Items</button></div>');
      $('#updateitemsbtnbtm').append('<div class=\"noPrint\"><button id="UpdateEstItems2">Update Items</button></div>');
      
      $('#PBaccordion').accordion({autoHeight:false}).show(500);
      $('button').button();
      $('input').css('text-align','center');
      
      //$('#LoadStatus').empty();
      
      return false;
   }
   
   function BuildCustomerEstimateInfo(oid,estid)
   {
      $.ajax({
            cache:false,
            type : 'GET',
            url : ajxscript,
            data: {
                'call' : 'get_CustomerInfo_Estimate_Edit',
                'oid' : oid,
                'estid' : estid,
                'optype': 'html'
            },
            dataType: 'html',
            success: function (data) {
               $('#dispCustomerInfo').html(data).show(500);
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
               $('#dispCustomerInfo').html(errorThrown).show(500);
            }
      });
      
      return false;
   }
   
   $('#returnRetailBreakdown').live('click',function(event){
      event.preventDefault();
      $('body').append('<form id="frmReturnRetailBrkDwn" method="POST"></form>');
      
      var thisfrm=$('#frmReturnRetailBrkDwn');
      thisfrm.append('<input name="action" value="est">');
      thisfrm.append('<input name="call" value="view_retail">');
      thisfrm.append('<input name="esttype" value="E">');
      thisfrm.append('<input name="estid" value="'+esid+'">');
      thisfrm.submit();
      return false;
   });

   $('#UpdateEstItems1').live('click',function(event){
      event.preventDefault();
      $('#frmEstEditItems').submit();
   });
   
   $('#UpdateEstItems2').live('click',function(event){
      event.preventDefault();
      $('#frmEstEditItems').submit();
   });
   
});