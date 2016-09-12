$(document).ready(function()
{
   var show_warning  = 0;
   var newest_tab_id = parseInt($.cookie("newest_view_menu_tab")) || 0;
   var aoid          = parseInt($('#active_oid').val()) || 0;
   var asid          = parseInt($('#active_sid').val()) || 0;
   var acid          = parseInt($('#active_cid').val()) || 0;
   var esid          = parseInt($('#active_estid').val()) || 0;
   var LoadStatus    = '<div id="LoadStatus"><img src="../images/mozilla_blu.gif"> Loading, please wait...</div>';
   var ajxscript     = 'ajx/ajax_estimatesys_req.php';
   
   $('button').button();
   $('#LoadStatus').hide();
   
   LoadCustomer(aoid,acid);
   LoadBuild(aoid);
   
   function LoadCustomer(oid,cid)
   {
      $.ajax({
         cache:false,
         type : 'GET',
         url : ajxscript,
         data: {
            'call' : 'get_CustomerInfo',
            'oid' : oid,
            'cid' : cid,
            'optype': 'html'
         },
         dataType: 'html',
         success: function (data) {
            $('#CustomerInfo > fieldset').append(data);
         },
         error : function(XMLHttpRequest, textStatus, errorThrown) {
            alert('Error: ' + errorThrown);
         }
      });
      
      return false;
   }
   
   function LoadBuild(oid)
   {
      $.ajax({
         cache:false,
         type : 'GET',
         url : ajxscript,
         data: {
            'call' : 'get_BuildBase',
            'oid' : oid,
            'optype': 'html'
         },
         dataType: 'html',
         success: function (data) {
            $('#BuildInfo > fieldset').append(data);
         },
         error : function(XMLHttpRequest, textStatus, errorThrown) {
            alert('Error: ' + errorThrown);
         }
      });
      
      return false;
   }
   
   function LoadOffice()
   {
      
      return false;
   }
   
   function LoadCommission()
   {
      
      return false;
   }
   
   function BuildAccordion(el)
   {
      var RetailCategories_JSON = (function(){
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
      
      var locel='EstPBAccordion';
      
      $(el).append('<div id="'+locel+'"></div>');
      
      $.each(RetailCategories_JSON,function(i,v){
         var tout='';         
         tout+='<h3><a class="ahrefset" id="'+v.catid+'" href="#">'+v.catname+'</a></h3>';
         tout+='<div id="itemset_'+v.catid+'"></div>';
         
         $('#'+locel).append(tout);
      });
      
      $('#'+locel).accordion({
         autoHeight:false,
         create: function(event,ui){
            var ael = parseInt($(this).find('.ui-state-active').children('.ahrefset').attr('id'));
            getRetailItems(aoid,ael,esid,'#itemset_'+ael);
         },
         change: function(event,ui){
            var ael = parseInt($(this).find('.ui-state-active').children('.ahrefset').attr('id'));
            getRetailItems(aoid,ael,esid,'#itemset_'+ael);
         }
      });
      
      return false;
   }
   
   function getRetailItems(goid,gcatid,geid,el)
   {
      var RetailItems_JSON =  (function(){
         var json = null;
         $.ajax({
            'async': false,
            'global': false,
            'url': ajxscript+'?call=get_RetailItems&goid='+goid+'&catid='+gcatid+'&estid='+geid+'&optype=json',
            'dataType': "json",
            'success': function (data) {
               json = data;
            }
         });
      
         return json;
      })();
      
      var tout='';
      tout+='<table class="inner_borders" border=1 width="400px">';
      tout+='<tr class="odd"><td align="center"><i>Code</i></td><td align="center"><i>Description</i></td><td align="center"><i>Price</i></td><td></td></tr>';
         
         $.each(RetailItems_JSON,function(ii,iv){
            if (iv.qtype==32)
            {
               tout+='<tr class="darkgray"><td colspan="5"><b>'+iv.ritem+'</b></td></tr>';
            }
            else
            {
               tout+='<tr class="even">';
               tout+='  <td class="pbrid" align="center">'+iv.rid+'</td>';
               tout+='  <td class="pbitem" align="left">'+iv.ritem+'</td>';
               tout+='  <td class="pbprice" align="right" width="40px">'+iv.rprice+'</td>';
               tout+='  <td width="20px" align="center"><img class="addItemtoEst" src="images/action_add.gif" title="Add to Estimate"></td>';
               tout+='</tr>';
            }
         });

         tout+='</table>';
         
         $(el).append(LoadStatus);
         $('#LoadStatus').show();
         
         $(el).empty().append(tout);
         $('input').css('text-align','center');
         $(".addtoEst").css("cursor","pointer")
         
         return false;
   }
   
   function BuildCustomerInfo(oid,cid)
   {
      $.ajax({
            cache:false,
            type : 'GET',
            url : ajxscript,
            data: {
                'call' : 'get_CustomerInfo_Estimate',
                'oid' : oid,
                'cid' : cid,
                'optype': 'html'
            },
            dataType: 'html',
            success: function (data) {
               $('#CustomerInfo').html(data).show(500);
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
               $('#CustomerInfo').html(errorThrown).show(500);
            }
      });
      
      return false;
   }
   
   function CloseEstPBDialog()
   {
      $('#EstPBDialog').remove();
      return false;
   }
   
   function EstPBDialog(el)
   {
      var dialog =
      $(el).dialog({
         title: "Pricebook",
         bgiframe: true,
         modal: true,
         draggable: false,
         autoOpen: false,
         width: 475,
         resizable: false,
         position: {
                  my: 'right top',
                  at: 'left top',
                  of: $('#OpenEstPBDialog')
            },
         close: function() {
            CloseEstPBDialog();
         }
      })
      .dialog('open');
   }
   
   $('#OpenEstPBDialog').click(function(event) {
      event.preventDefault();
      var el='EstPBDialog';
      
      $('#'+el).remove();
      $('body').append('<div id="'+el+'"></div>');
      EstPBDialog('#'+el);
      BuildAccordion('#'+el);
      return false;
   });
   
   function BidItemDialog(el)
   {
      var dialog =
      $(el).dialog({
         title: "Bid Item",
         bgiframe: true,
         modal: true,
         draggable: false,
         autoOpen: false,
         width: 250,
         height: 200,
         resizable: false,
         position: {
                  my: 'right top',
                  at: 'left top',
                  of: $('#OpenBidItemDialog')
         },
         close: function() {
            CloseBidItemDialog();
         },
         buttons: {
            Add: function() {
               addBidtoEst();
               CloseBidItemDialog();
            },
            Cancel: function() {
               CloseBidItemDialog();
            }
         }
      })
      .dialog('open');
   }
   
   $('#OpenBidItemDialog').click(function(event) {
      event.preventDefault();
      var el='BidItemDialog';
      
      $('#'+el).remove();
      $('body').append('<div id="'+el+'"></div>');
      BidItemDialog('#'+el);
      BidItemEntry('#'+el);
      return false;
   });
   
   function CloseBidItemDialog()
   {
      $('#BidItemDialog').remove();
      return false;
   }
   
   function BidItemEntry(el)
   {
      var tout='Bid Description<br><textarea id="BidInfo"></textarea><br>Bid Amount<br><input type="text" id="BidAmt" value="0.00">';
      
      $(el).empty().append(tout);
      return false;
   }

   $('.addItemtoEst').live('click',function(event){
      var bsid=$(this).parent().parent(); //table element
      var trid=bsid.children('.pbrid').html();
      var titm=bsid.children('.pbitem').html();
      var tcat=bsid.parent().parent().parent().parent().find('.ui-state-active').children('a.ahrefset').text();
      var tprc=bsid.children('.pbprice').html();
      
      var tout='';
      tout+='<tr class="even">';
      tout+='  <td class="eRid" align="center" width="40px">'+trid+'</td>';
      tout+='  <td class="eCategory" width="100px">'+tcat+'</td>';
      tout+='  <td class="eItem">'+titm+'</td>';
      tout+='  <td class="eQuantity" align="center" width="40px" contenteditable="true">1</td>';
      tout+='  <td class="ePrice" align="right" width="40px">'+tprc+'</td>';
      tout+='  <td class="tPrice" align="right" width="40px" contenteditable="false">'+tprc+'</td>';
      tout+='  <td align="center" width="20px"><img class="delfromest" src="images/action_delete.gif"></td>';
      tout+='</tr>';
      
      $('#eItemDetail').append(tout);
      $('#EstimatePrice').html(calcEstimate()).formatCurrency({symbol:'',groupDigits:false});
      
      return false;
   });
   
   $('.delfromest').live('click',function(event){
      event.preventDefault();
      
      $(this).parent().parent().remove();
      $('#EstimatePrice').html(calcEstimate()).formatCurrency({symbol:'',groupDigits:false});
      return false;
   });
   
   $('.eQuantity').live('keyup',function(){
      var quan=parseFloat($(this).html());

      if (!isNaN(quan))
      {
         var uprice=parseFloat($(this).parent().children('.ePrice').html());
         var cprice=quan*uprice;
         
         $(this).parent().children('.tPrice').html(cprice).formatCurrency({symbol:'',groupDigits:false});
         $('#EstimatePrice').html(calcEstimate()).formatCurrency({symbol:'',groupDigits:false});
      }
      
      return false;
   });
   
   function addBidtoEst()
   {
      var titm=$('#BidInfo').val();
      var tprc=$('#BidAmt').val();
      
      var tout='';
      tout+='<tr class="even">';
      tout+='  <td class="eRid" align="center" width="40px">BID</td>';
      tout+='  <td class="eCategory" width="100px" contenteditable="false">Bid Item</td>';
      tout+='  <td class="eItem" contenteditable="true">'+titm+'</td>';
      tout+='  <td class="eQuantity" align="center" width="40px" contenteditable="false">1</td>';
      tout+='  <td class="ePrice" align="right" width="40px" contenteditable="false"></td>';
      tout+='  <td class="tPrice" align="right" width="40px" contenteditable="true">'+tprc+'</td>';
      tout+='  <td align="center" width="20px"><img class="delfromest" src="images/action_delete.gif"></td>';
      tout+='</tr>';
      
      $('#eItemDetail').append(tout);
      calcEstimate();
      
      return false;
   }
   
   function calcEstimate()
   {
      var tout=0;
      
      $.each($('.tPrice'), function(){
         tout=tout+parseFloat($(this).html());
      });
      
      return tout;
   }
   
   $('#UpdateEstItems2').live('click',function(event){
      event.preventDefault();
      $('#frmEstEditItems').submit();
   });
   
});