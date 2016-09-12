$(document).ready(function()
{
   var ajxscript     = 'subs/ajax_leads_req.php';
   var spinnerIMG    = '<em>Loading...</em>';
   var clearcontent='';
   
   function get_LeadDatabyName() {
      var clname=$('#clname').val();
      var oid=parseInt($('#soid').val());
      
      if (!isNaN(oid) && clname.length >= 2) {
         $.ajax({
            cache:false,
            type : 'GET',
            url : ajxscript,
            dataType : 'html',
            data: {
               call : 'leads',
               subq : 'get_LeadsbyName_list',
               oid : oid,
               clname : clname,
               optype : 'table'
            },
            success : function(data){
               $('#LeadNameData')
                  .css('background', 'lightgrey')
                  .css('border', '1px')
                  .css('border-style', 'solid')
                  .css('border-color', 'grey')
                  .css('border-radius', '2px')
                  .css('position', 'absolute')
                  .css('zindex', 1000)
                  .html(data).show(500);
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
               $('#LeadNameData').html(textStatus).show(500);
            }
         });
      }
   }
   
   function get_LeadDatabyCompany()
   {
      var cpname=$('#cpname').val();
      var oid=parseInt($('#soid').val());
      
      if (!isNaN(oid) && cpname.length >= 2)
      {
         $.ajax({
            cache:false,
            type : 'GET',
            url : ajxscript,
            dataType : 'html',
            data: {
               call : 'leads',
               subq : 'get_LeadsbyCompany_list',
               oid : oid,
               cpname : cpname,
               optype : 'table'
            },
            success : function(data){
               $('#CompanyNameData').html(data).show(500);
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
               $('#CompanyNameData').html(textStatus).show(500);
            }
         });
      }
   }
   
   $('#clname').live('keyup',function(){
      get_LeadDatabyName();
   });
   
   $('#cpname').keyup(function(){
      get_LeadDatabyCompany();
   });
   
   $('#removeLeadZList').live('click',function(){
      $('#LeadNameData').empty();
   });
   
   $('#cpname').blur(function(){
      $('#CompanyNameData').empty();
   });
   
   //Copy contents of Current Address to Site Address if 'Same as above' is checked
   $('#caddr1').keyup(function(){
      if ($('#ssame').attr('checked'))
      {
         $('#saddr1').val($('#caddr1').val());
      }
   });
   
   $('#ccity').keyup(function(){
      if ($('#ssame').attr('checked'))
      {
         $('#scity').val($('#ccity').val());
      }
   });
   
   $('#cstate').keyup(function(){
      if ($('#ssame').attr('checked'))
      {
         $('#sstate').val($('#cstate').val());
      }
   });
   
   $('#czip1').keyup(function(){
      if ($('#ssame').attr('checked'))
      {
         $('#szip1').val($('#czip1').val());
      }
   });
   
   $('#czip2').keyup(function(){
      if ($('#ssame').attr('checked'))
      {
         $('#szip2').val($('#czip2').val());
      }
   });
   
   $('#ccounty').keyup(function(){
      if ($('#ssame').attr('checked'))
      {
         $('#scounty').val($('#ccounty').val());
      }
   });
   
   $('#cmap').keyup(function(){
      if ($('#ssame').attr('checked'))
      {
         $('#smap').val($('#cmap').val());
      }
   });
   
   $('#ssame').click(function(){
      if ($('#ssame').attr('checked'))
      {
         $('#saddr1').val($('#caddr1').val());
         $('#scity').val($('#ccity').val());
         $('#sstate').val($('#cstate').val());
         $('#szip1').val($('#czip1').val());
         $('#szip2').val($('#czip2').val());
         $('#scounty').val($('#ccounty').val());
         $('#smap').val($('#cmap').val());
      }
      else
      {
         $('#saddr1').val(clearcontent);
         $('#scity').val(clearcontent);
         $('#sstate').val(clearcontent);
         $('#szip1').val(clearcontent);
         $('#szip2').val(clearcontent);
         $('#scounty').val(clearcontent);
         $('#smap').val(clearcontent);
      }
   });
   
   $('.selectLDbyCID').live('click',function(event){
      //event.preventDefault();
      alert($(this).html()+' Linkage not yet active');
   });
   //End Copy Contents
});