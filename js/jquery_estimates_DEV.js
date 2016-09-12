$(document).ready(function() {
   $('.setpointer').live('hover',function() {
         $(this).css('cursor','pointer');
      },
      function() {
         $(this).css('cursor','auto');
      return false;
   });
   
   $('#EST_STRING_SEARCH_FRM').live('submit',function(e){
      e.preventDefault();
      var i=$('#sval_input').val();
      
      if (i.length!=0) {
         $("#EstimateSearchResults").empty().html('<img src="images/mozilla_blu.gif"> Searching...');
         getEstimateSearchResult($(this));
      }
      else {
         alert('Enter a Last Name or partial Last Name');
      }
   });
   
   $('#EST_SREP_SEARCH_FRM').live('submit',function(e){
      e.preventDefault();
      $("#EstimateSearchResults").empty().html('<img src="images/mozilla_blu.gif"> Searching...');
      getEstimateSearchResult($(this));
   });
   
   if ($('#CartCatalogTotal').length > 0) {
      calcTotals();
   }
   
   $('#refreshCartTotal').live('click',function(e){
      calcTotals();
   });
   
   $('#PriceBookControl').live('click',function(e){
      var opb=$(this).hasClass('OpenPB');
      var cpb=$(this).hasClass('ClosePB');
      var oid=parseInt($('#sysoid').val());
      
      if (opb) {
         $('#CartDisplay').hide('blind');
         if ($('.pbcontainer').length==0) {
            getPriceBook(oid,'E');
         }
         else {
            $('#PriceBookDisplay').show('blind');
         }
         
         $(this).removeClass('OpenPB').addClass('ClosePB').button({label:'Breakdown'});
      }
      
      if (cpb) {
         $('#PriceBookDisplay').hide('blind');
         $('#CartDisplay').show('blind');
         $(this).removeClass('ClosePB').addClass('OpenPB').button({label:'Pricebook'});
      }
   });
   
   $('#refreshPriceBook').live('click',function(e){
      $('.pbcontainer').remove();
      getPriceBook(oid,'E');
   });
   
   $('#ClosePriceBook').live('click',function(e){
      $('#PriceBookDisplay').hide('slide');
      $('#CartDisplay').show();
   });
   
   $('.CartItemDelete').live('click',function(e){
      var lel=$(this).parent().parent();
      var iobj=new Object();
      iobj.call='removeCartItem';
      iobj.optype='json';
      iobj.oid=parseInt($('#sysoid').val());
      iobj.estid=parseInt($('#sysestid').val());
      iobj.crtid=parseInt($(this).parent().children('.crtid').html());
      
      if (!isNaN(iobj.oid) && !isNaN(iobj.estid) && !isNaN(iobj.crtid)) {
         CartRemoveItem(iobj,lel);
         calcTotals();
      }
   });
   
   $('.CartItemAdd').live('click',function(e){
      e.preventDefault();
      var fqn=parseInt($('#ps1').val());
      
      if (!isNaN(fqn) && fqn!=0) {
         var iobj=new Object();
         var ipe=$(this).parent().parent().parent().children('tr.irow').children('td.iinfo');
         var iqn=ipe.children('.iquan');
         var ipr=ipe.children('.iprice');
         var ibo=$(this).parent().parent().parent().children('.bidtextline').children('.bidtext').children('.ibidtext');
         
         iobj.iid=parseInt(ipe.children('.iid').html());
         iobj.iqt=parseInt(ipe.children('.iqtype').html());
         
         if (!isNaN(iobj.iid) && !isNaN(iobj.iqt)) {
            iobj.eid=$('#sysestid').val();
            iobj.bqn=(iqn.length > 0)?ipe.children('.iquan').val():1;
            iobj.bnm=ipe.parent().children('.iitem').children('.iname').html();
            //iobj.bdo=$(this).parent().parent().parent().children('.bidtextline').children('.bidtext').children('.ibidtext');
            iobj.bct='';

            if (ipr.length > 0) {
               if (ipr.is('input')) {
                  iobj.bpr=ipe.children('.iprice').val();
               }
               else {
                  iobj.bpr=ipe.children('.iprice').html();
               }
            }
            else {
               iobj.bpr=0;
            }
            
            if (ibo.length > 0) {
               //alert('BIDT');
               //alert(ibo.val());
               iobj.bdt=ibo.val();
            }
            
            if (iobj.bqn > 0) {
               CartAddItem(iobj);
            }
            else {
               alert('Quantity Error.\n\nQuantity not set for selected item.')
            }
         }
         else {
            alert('Pricebook Item Error.\nContact Support if this condition persists.')
         }
      }
      else {
         alert('Perimeter not set');
      }
   });
   
   $('#ContractTotalSave').live('click',function(e){
      e.preventDefault();
      var iobj=new Object();
      iobj.call='updateContractAmt';
      iobj.optype='json';
      iobj.oid=$('#sysoid').val();
      iobj.eid=$('#sysestid').val();
      iobj.camt=parseFloat($('#CartContractTotal').html());
      
      if (!isNaN(iobj.camt)) {
         updateContractAmt(iobj);
      }
   });
   
   $('.exp_tline').live('click',function(e){
      var itl=$(this).parent().parent().parent().children('tr.bidtextline');
      
      if (itl.is(":visible")) {
         itl.hide();
      }
      else {
         itl.show();
         itl.children('.bidtext').children('.ibidtext').focus();
      }
   });
   
   $('.chngval').live('change',function(e){
      e.preventDefault();
      var elobj= new Object;
      elobj.call='updatePoolDimensions';
      elobj.optype='json';
      elobj.oid=$('#sysoid').val();
      elobj.eid=$('#sysestid').val();
      elobj.frmfld=$(this).attr('id');
      elobj.fldval=$(this).val();

      if (elobj.frmfld=='ps1') {
         updateCartPFTItems();
      }
      
      updateFrmValue(elobj);
   });
});

function updateCartPFTItems() {
   $('.crtpft').each(function(i,v){
      alert($(this).html());
      //alert(v.val());
   });
}

function updateFrmValue(iobj) {   
   $.post('subs/ajax_estimate_req.php',iobj, function(data){
      if (data.err==0) {
         calcTotals();
         
         if (data.ia!=0) {
            $('#fia').html(data.ia);
         }
         
         if (data.gl!=0) {
            $('#fgl').html(data.gl);
         }
         
         setStatusMsg('Pool Dimensions updated... ')
      }
      else {
         alert('Update Error');
      }
   });
}

function calcTotals() {
   var con=parseFloat($('#CartContractTotal').html());
   var ouc=(parseFloat($('#OUCalcVar').html()) *.01);
   var crt=calcItems($('.ItemPrice'));
   var cct=calcItems($('.ItemComm'));
   var act=0; // AdjComm Items
   var dif=(con - crt);
   
   if (!isNaN(ouc) && !isNaN(dif) && ouc!=0 && dif!=0) {
      var oou=(dif * ouc);
      $('#CartDiffTotal').html(dif).formatCurrency({symbol:'',groupDigits:false});
      $('#CartOUTotal').html(oou).formatCurrency({symbol:'',groupDigits:false});
      cct+=(oou+act);
   }

   $('#CartCatalogTotal').html(crt).formatCurrency({symbol:'',groupDigits:false});
   $('#CommGrandTotal').html(cct).formatCurrency({symbol:'',groupDigits:false});
}

function updateContractAmt(iobj) {
   $.post('subs/ajax_estimate_req.php',iobj, function(data){
      if (data.err==0) {
         calcTotals();
         setStatusMsg('Contract Amount Updated')
      }
      else {
         alert('Update Error');
      }
   });
}

function setStatusMsg(msg) {
   $('#statusmessage').text(msg).animate({'margin-bottom':0},200);
   setTimeout( function(){$('#statusmessage').animate({'margin-bottom':-25},200);}, 5*500);
}

function CartRemoveItem(iobj,el) {
   $.post('subs/ajax_estimate_req.php',iobj, function(data){
      if (data.err==0) {
         el.remove();
         calcTotals();
         setStatusMsg('Item removed... ')
      }
      else {
         alert('Update Error');
      }
   });
}

function CartAddItem(iobj) {
   //alert('iid:'+iobj.iid+'\ntype:'+iobj.iqt+'\nquan:'+iobj.bqn+'\nprice:'+iobj.bpr);
   iobj.call='CalcPBItems';
   iobj.optype='json';
   iobj.ps1=parseInt($('#ps1').val());
   iobj.ps2=parseInt($('#ps2').val());
   iobj.ps5=parseInt($('#ps5').val());
   iobj.ps6=parseInt($('#ps6').val());
   iobj.ps7=parseInt($('#ps7').val());
   iobj.fia=parseInt($('#fia').html());
   iobj.fgl=parseInt($('#fgl').html());
   
   $.get('subs/ajax_estimate_req.php',iobj, function(data){
      if (data.err==0) {
         var dout=frmtCartLineItem(data,iobj);
         
         $('#CartTable').append(dout);
         setStatusMsg('Breakdown updated...');
         
         calcTotals();
      }
      else {
         alert('Item Lookup Error');
      }
   });
}

function frmtCartLineItem(data,iobj) {
   var out='';
   var blt=(data.bullet>=1)?'cartbulletitemicon':'';
   var bdt=(data.qtype==33)?data.atrib:'';
   out+='<tr class="CartLineItem">';
   out+='<td class="wh" align="left">'+data.catname+'</td>';
   out+='<td class="wh '+blt+'" align="left">'
   out+=data.item;
   out+='<br>'+bdt;
   out+='</td>';
   out+='<td class="wh pbquan" align="center"><span class="ItemQuan">'+data.calcqn+'</span></td>';
   out+='<td class="wh" align="center">'+data.mname+'</td>';
   out+='<td class="wh pbprice" align="right"><span class="ItemPrice">'+data.calcrp+'</span></td>';
   out+='<td class="wh pbcomm" align="right"><span class="ItemComm">'+data.calccm+'</span></td>';
   out+='<td class="wh pbinfo" align="center">';
   out+='   <span class="pbid" style="display:none;">'+data.iid+'</span>';
   out+='   <span class="crtid" style="display:none;">'+data.edid+'</span>';
   out+='   <span class="crtunique" style="display:none;">'+data.poolcalc+'</span>';
   out+=(data.mtype==4)?'   <span class="crtpft" style="display:none;">'+data.mtype+'</span>':'';
   out+='   <span class="CartItemDelete setpointer noPrint"><img src="images/action_delete.gif"></span>';
   out+='</td>';
   out+='</tr>';
   
   return out;
}

function ClosePBOpenCart() {
   $('#PriceBookDisplay').hide('slide');
   $('#CartDisplay').show();
}

function getPriceBook(toid,jtype,el) {
   $.ajax({
      cache:false,
      type : 'GET',
      url : 'subs/ajax_estimate_req.php',
      dataType : 'html',
      data: {
         call : 'getPriceBook',
         oid : toid,
         jtype : jtype
      },
      success : function(data) {
         $('#PriceBookDisplay').append(data).show();
      },
      error : function(XMLHttpRequest, textStatus, errorThrown) {
         alert(textStatus);
      }
   });
}

function calcItems(el) {
   var out=0;
   $.each(el,function(k,v){
      var ip=parseFloat($(v).html());
      
      if (!isNaN(ip)) {
         out+=ip;
      }
   });
   return out;
}

function getEstimateSearchResult(el) {
   var dvars=getVars(el);
   $.get('subs/ajax_estimate_req.php',dvars, function(result){
	  $("#EstimateSearchResults").empty().html(result).show();
   });
}

function getVars(el) {
   var i=el.find(':input');
   var dataObj={};
   
   $.each(i,function(k,v){
      dataObj[v.name]=v.value;
   });
   
   return dataObj;
}