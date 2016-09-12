$(document).ready(function()
{
   var ajxscript ='subs/ajax_reports_req.php';
   
   $('#d1').datepicker();
   $('#d2').datepicker();
   
   $("#validatedialog").dialog({
      autoOpen: false,
     draggable: false,
      bgiframe: true,
      position: 'top',
      height: 175,
      width: 300,
      modal: true,
      buttons: {
         Save: function() {
               $('#ValidateSRPage').submit();
               $(this).dialog('close');
         },
         Cancel: function() {
            $(this).dialog('close');
         }
      }
   });
   
   function getadjustform()
   {
      $.ajax({
          cache:false,
          type : 'POST',
          url : ajxscript,
          dataType : 'html',
          data: {
              call : 'reports',
              subq : 'getSCAdjustForm',
              sid : $('#srsid').val(),
              optype : 'table'
          },
          success : function(data){
              $('#adjustdialog').html(data);
          },
          error : function(XMLHttpRequest, textStatus, errorThrown) {
              alert(textStatus);
          }
      });
      return false;
   }

   function openadjustDialog(el)
   {
      $('#adjustdialog').empty().remove();
      $('body').append('<div id="adjustdialog"></div>');
      
      getadjustform();
      
      var dialog =
      $("#adjustdialog").dialog({
         title: 'Manual Adjust',
         autoOpen: false,
         draggable: false,
         bgiframe: true,
         height: 200,
         width: 300,
         modal: true,
         position: {
             my: 'right top',
             at: 'right bottom',
             of: el
         },
         close: closeadjustdialog(),
         buttons: {
             Save: function() {
                if ($('#adj_amt').val()!='')
                {
                   $('#AdjustSRPage').submit();
                   closeadjustdialog();
                }
                else
                {
                   alert('Enter an Adjust Amount');
                }
             },
             Cancel: function() {
                closeadjustdialog();
             }
         }
      }).dialog('open');
   }
   
   function openvalidateDialog(el)
   {
      $('#validateDialog').empty().remove();
      $('body').append('<div id="validatedialog"></div>');
      
      getvalidateform();
      
      var dialog =
      $("#validatedialog").dialog({
         title: 'Validate Page',
         autoOpen: false,
        draggable: false,
         bgiframe: true,
         height: 200,
         width: 300,
         modal: true,
         position: {
             my: 'right bottom',
             at: 'left top',
             of: el
          },
          close: closevalidatedialog(),
         buttons: {
            Save: function() {
                  $('#ValidateSRPage').submit();
                  closevalidatedialog();
            },
            Cancel: function() {
               closevalidatedialog();
            }
         }
      }).dialog('open');
   }
   
   function getvalidateform()
   {
      $.ajax({
          cache:false,
          type : 'POST',
          url : ajxscript,
          dataType : 'html',
          data: {
              call : 'reports',
              subq : 'getSCValidateForm',
              sid : $('#srsid').val(),
              tbal : $('#thistbal').val(),
              optype : 'table'
          },
          success : function(data){
              $('#validatedialog').html(data);
          },
          error : function(XMLHttpRequest, textStatus, errorThrown) {
              alert(textStatus);
          }
      });
      return false;
   }
   
   function opendeleteDialog(el)
   {
      $('#deletedialog').empty().remove();
      $('body').append('<div id="deletedialog"></div>');
      
      getdeleteform(el);
      
      var dialog =
      $("#deletedialog").dialog({
         title: 'Delete Entry',
         autoOpen: false,
        draggable: false,
         bgiframe: true,
         height: 200,
         width: 300,
         modal: true,
         position: {
             my: 'right bottom',
             at: 'left top',
             of: el
          },
          close: closedeletedialog(),
         buttons: {
            Delete: function() {
                  $('#SCDeleteItemForm').submit();
                  closedeletedialog();
            },
            Cancel: function() {
               closedeletedialog();
            }
         }
      }).dialog('open');
   }
   
   function getdeleteform(el)
   {
      var thid = parseInt(el.parent().children('.thishid').val());
      $.ajax({
          cache:false,
          type : 'POST',
          url : ajxscript,
          dataType : 'html',
          data: {
              call : 'reports',
              subq : 'getSCDeleteItemForm',
              sid : $('#srsid').val(),
              hid : thid,
              optype : 'table'
          },
          success : function(data){
              $('#deletedialog').html(data);
          },
          error : function(XMLHttpRequest, textStatus, errorThrown) {
              alert(textStatus);
          }
      });
      return false;
   }
   
   function openlegendDialog(el)
   {
      $('#legenddialog').empty().remove();
      $('body').append('<div id="legenddialog"></div>');

      getlegendtable()
      
      var dialog =
      $("#legenddialog").dialog({
         title: 'Sales & Commission Legend',
         autoOpen: false,
        draggable: false,
         bgiframe: true,
         height: 300,
         width: 400,
         modal: true,
         position: {
             my: 'left top',
             at: 'right bottom',
             of: el
          },
         close: closelegenddialog(),
         buttons: {
            Close: function() {
               closelegenddialog();
            }
         }
      }).dialog('open');
   }
   
   function getlegendtable()
   {
      $.ajax({
          cache:false,
          type : 'POST',
          url : ajxscript,
          dataType : 'html',
          data: {
              call : 'reports',
              subq : 'getSCLegend',
              optype : 'table'
          },
          success : function(data){
              $('#legenddialog').html(data);
          },
          error : function(XMLHttpRequest, textStatus, errorThrown) {
              alert(textStatus);
          }
      });
      return false;
   }
   
   $('#openlegenddialog').click(function() {
      openlegendDialog($(this));
   });
   
   $('#openadjustdialog').click(function() {
      openadjustDialog($(this));
   });

   $('#openvalidatedialog').click(function() {
      openvalidateDialog($(this))
   });
   
   $('.SCDeleteItem').click(function() {
      opendeleteDialog($(this));
   });
   
   function closelegenddialog()
   {
      $('#legenddialog').empty().remove().dialog('close');
   }
   
   function closeadjustdialog()
   {
      $('#adjustdialog').empty().remove().dialog('close');
   }
   
   function closevalidatedialog()
   {
      $('#validatedialog').empty().remove().dialog('close');
   }
   
   function closedeletedialog()
   {
      $('#deletedialog').empty().remove().dialog('close');
   }
});