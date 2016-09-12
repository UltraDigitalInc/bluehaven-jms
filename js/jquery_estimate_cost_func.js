$(document).ready(function()
{
   $('.OpenBidCostAddDialog').live('click',function(event) {
      event.preventDefault();
      var objVals=new Array();
      objVals['act']=$(this).parent().children('.bca_act').val();
      objVals['oid']=$(this).parent().children('.bca_oid').val();
      objVals['sid']=$(this).parent().children('.bca_sid').val();
      objVals['cid']=$(this).parent().children('.bca_cid').val();
      objVals['jid']=$(this).parent().children('.bca_jid').val();
      objVals['jadd']=$(this).parent().children('.bca_jadd').val();
      objVals['pbcid']=$(this).parent().children('.bca_pbcid').val();
      objVals['rdbid']=$(this).parent().children('.bca_rdbid').val();
      objVals['cdbid']=$(this).parent().children('.bca_cdbid').val();
      objVals['cstid']=$(this).parent().children('.bca_cstid').val();
   
      BidCostAddDialog(objVals,$(this));
   });
   
   function CloseBidCostAddDialog()
   {
      $('#BidCostAddDialog').empty().dialog('close');
      return false;
   }
   
   function BidCostAddDialog(objVals,el)
   {
      $('#BidCostAddDialog').remove();
      $('body').append('<div id="BidCostAddDialog"></div>');
      get_BCADrillDetail(objVals,'#BidCostAddDialog');
      var dialog = 
      $('#BidCostAddDialog').dialog({
         title: 'Add Bid Item Cost',
         bgiframe: true,
         autoOpen: false,
         resizeable: false,
         width: 325,
         height: 325,
         modal: true,
         position: {
            my: 'left top',
            at: 'right top',
            of: el
         },
         close: CloseBidCostAddDialog(),
         buttons: {
            Cancel: function() {
               CloseBidCostAddDialog();
            },
            Add: function() {
               $('#BidCostAddForm').submit();
            }
         }
      }).dialog('open');
   }
   
   function get_BCADrillDetail(objVals,pel)
   {
      $.ajax({
         cache:false,
         type : 'GET',
         url : 'subs/ajax_drilldetail.php',
         dataType : 'html',
         data: {
            call : 'bidadd',
            action : objVals['act'],
            officeid : objVals['oid'],
            sid : objVals['sid'],
            cid : objVals['cid'],
            jid : objVals['jid'],
            jadd : objVals['jadd'],
            pb_code : objVals['pbcid'],
            rdbid : objVals['rdbid'],
            cdbid : objVals['cdbid'],
            costid : objVals['cstid'],
            optype : 'table'
         },
         success : function(data){
            $(pel).html(data).show(500);
         },
         error : function(XMLHttpRequest, textStatus, errorThrown) {
            alert(textStatus);
         }
      });
		
      return true;
   }
   
   $('.OpenBidCostViewDialog').live('click',function(event) {
      event.preventDefault();
      var objVals=new Array();
      objVals['act']=$(this).parent().children('.bca_act').val();
      objVals['oid']=$(this).parent().children('.bca_oid').val();
      objVals['sid']=$(this).parent().children('.bca_sid').val();
      objVals['cid']=$(this).parent().children('.bca_cid').val();
      objVals['jid']=$(this).parent().children('.bca_jid').val();
      objVals['pbcid']=$(this).parent().children('.bca_pbcid').val();
      objVals['rdbid']=$(this).parent().children('.bca_rdbid').val();
   
      BidCostViewDialog(objVals,$(this));
   });
   
   function CloseBidCostViewDialog()
   {
      $('#BidCostViewDialog').empty().dialog('close');
      return false;
   }
   
   function BidCostViewDialog(objVals,el)
   {
      $('#BidCostViewDialog').remove();
      $('body').append('<div id="BidCostViewDialog"></div>');
      get_BCAView(objVals,'#BidCostViewDialog');
      var dialog = 
      $('#BidCostViewDialog').dialog({
         title: 'Bid Item Cost Total',
         bgiframe: true,
         autoOpen: false,
         resizeable: false,
         width: 375,
         height: 375,
         modal: true,
         position: {
            my: 'left top',
            at: 'right top',
            of: el
         },
         close: CloseBidCostViewDialog(),
         buttons: {
            Close: function() {
               CloseBidCostViewDialog();
            }
         }
      }).dialog('open');
   }
   
   function get_BCAView(objVals,pel)
   {
      $.ajax({
         cache:false,
         type : 'GET',
         url : 'subs/ajax_drilldetail.php',
         dataType : 'html',
         data: {
            call : 'vac',
            action : objVals['act'],
            oid : objVals['oid'],
            sid : objVals['sid'],
            cid : objVals['cid'],
            jid : objVals['jid'],
            pb_code : objVals['pbcid'],
            rdbid : objVals['rdbid'],
            optype : 'table'
         },
         success : function(data){
            $(pel).html(data).show(500);
         },
         error : function(XMLHttpRequest, textStatus, errorThrown) {
            alert(textStatus);
         }
      });
		
      return true;
   }
   
   $('.OpenMPACostAddDialog').live('click',function(event) {
      event.preventDefault();
      var objVals=new Array();
      objVals['act']=$(this).parent().children('.mpa_act').val();
      objVals['oid']=$(this).parent().children('.mpa_oid').val();
      objVals['sid']=$(this).parent().children('.mpa_sid').val();
      objVals['cid']=$(this).parent().children('.mpa_cid').val();
      objVals['jid']=$(this).parent().children('.mpa_jid').val();
      objVals['jadd']=$(this).parent().children('.mpa_jadd').val();
      objVals['pbcid']=$(this).parent().children('.mpa_pbcid').val();
      objVals['rdbid']=$(this).parent().children('.mpa_rdbid').val();
      objVals['cdbid']=$(this).parent().children('.mpa_cdbid').val();
      objVals['cstid']=$(this).parent().children('.mpa_cstid').val();
   
      MPACostAddDialog(objVals,$(this));
   });
   
   function CloseMPACostAddDialog()
   {
      $('#MPACostAddDialog').empty().dialog('close');
      return false;
   }
   
   function MPACostAddDialog(objVals,el)
   {
      $('#MPACostAddDialog').remove();
      $('body').append('<div id="MPACostAddDialog"></div>');
      get_MPACostAdd(objVals,'#MPACostAddDialog');
      var dialog = 
      $('#MPACostAddDialog').dialog({
         title: 'Manual Phase Adjust',
         bgiframe: true,
         autoOpen: false,
         resizeable: false,
         width: 325,
         height: 275,
         modal: true,
         position: {
            my: 'left top',
            at: 'right top',
            of: el
         },
         close: CloseMPACostAddDialog(),
         buttons: {
            Cancel: function() {
               CloseMPACostAddDialog();
            },
            Add: function() {
               $('#MPAAddCostForm').submit();
            }
         }
      }).dialog('open');
   }
   
   function get_MPACostAdd(objVals,pel)
   {
      $.ajax({
         cache:false,
         type : 'GET',
         url : 'subs/ajax_drilldetail.php',
         dataType : 'html',
         data: {
            call : 'mpaadd',
            action : objVals['act'],
            officeid : objVals['oid'],
            sid : objVals['sid'],
            cid : objVals['cid'],
            jid : objVals['jid'],
            jadd : objVals['jadd'],
            pb_code : objVals['pbcid'],
            rdbid : objVals['rdbid'],
            cdbid : objVals['cdbid'],
            costid : objVals['cstid'],
            optype : 'table'
         },
         success : function(data){
            $(pel).html(data).show(500);
         },
         error : function(XMLHttpRequest, textStatus, errorThrown) {
            alert(textStatus);
         }
      });
		
      return true;
   }
   
   $('.OpenMPAViewDialog').live('click',function(event) {
      event.preventDefault();
      var objVals=new Array();
      objVals['act']=$(this).parent().children('.mpa_act').val();
      objVals['oid']=$(this).parent().children('.mpa_oid').val();
      objVals['sid']=$(this).parent().children('.mpa_sid').val();
      objVals['cid']=$(this).parent().children('.mpa_cid').val();
      objVals['jid']=$(this).parent().children('.mpa_jid').val();
      objVals['pbcid']=$(this).parent().children('.mpa_pbcid').val();
      objVals['rdbid']=$(this).parent().children('.mpa_rdbid').val();
   
      MPAViewDialog(objVals,$(this));
   });
   
   function CloseMPAViewDialog()
   {
      $('#MPAViewDialog').empty().dialog('close');
      return false;
   }
   
   function MPAViewDialog(objVals,el)
   {
      $('#MPAViewDialog').remove();
      $('body').append('<div id="MPAViewDialog"></div>');
      get_MPAView(objVals,'#MPAViewDialog');
      var dialog = 
      $('#MPAViewDialog').dialog({
         title: 'Manual Phase Adjust History',
         bgiframe: true,
         autoOpen: false,
         resizeable: false,
         width: 300,
         height: 250,
         modal: true,
         position: {
            my: 'left top',
            at: 'right top',
            of: el
         },
         close: CloseMPAViewDialog(),
         buttons: {
            Cancel: function() {
               CloseMPAViewDialog();
            }
         }
      }).dialog('open');
   }
   
   function get_MPAView(objVals,pel)
   {
      $.ajax({
         cache:false,
         type : 'GET',
         url : 'subs/ajax_drilldetail.php',
         dataType : 'html',
         data: {
            call : 'vmpa',
            action : objVals['act'],
            oid : objVals['oid'],
            sid : objVals['sid'],
            cid : objVals['cid'],
            jid : objVals['jid'],
            pb_code : objVals['pbcid'],
            rdbid : objVals['rdbid'],
            optype : 'table'
         },
         success : function(data){
            $(pel).html(data).show(500);
         },
         error : function(XMLHttpRequest, textStatus, errorThrown) {
            alert(textStatus);
         }
      });
		
      return true;
   }
});