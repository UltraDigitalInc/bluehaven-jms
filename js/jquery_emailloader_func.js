$(document).ready(function() {
   $('.deleteEmailFile').submit(function(e){
      var conf=confirm('You are about to delete this file.\nPress OK to continue.');
      if (!conf) {
         e.preventDefault();
      }
   });
   
   $('#addEmailKillEntry').live('click',function(e){
      e.preventDefault();
      var aoid =$('#active_office').val();
      var eadr=$('#addemailaddr').val();
      var ehst=$('#addemailhost').val();
      
      if (eadr.length > 0 && ehst.length) {
         var emailaddr=eadr+'@'+ehst;
         var conf=confirm('You are adding "'+ emailaddr +'" to the Email Kill File\n\nClick OK or Cancel');
         
         if (conf) {
            alert(eadr+'@'+ehst);
         }
      }
      else {
         alert('Enter a proper Email Address');
      }
      
   });
});