$(document).ready(function()
{
   var AddFolder           = $('<form method="POST"><input type="hidden" name="action" value="file"><input type="hidden" name="call" value="addfolder"><input type="text" name="foldername" size="25" maxlength="25"><input type="image" src="images/accept.png"></form>');
   var fs_edit_state_id    = parseInt($.cookie("fs_edit_state")) || 0;
   var fileselect_tab_id   = parseInt($.cookie("fileselect_tab")) || 0;
   var filelisting_tab_id  = parseInt($.cookie("filelisting_tab")) || 0;
   var fsoid               = $('#FFOID').val();
   var ajxscript	       = 'subs/ajax_file_req.php';
   var fsurl               = 'subs/ajax_file_req.php?call=file&subq=show_FileStoreTreeJSON&oid=' + fsoid;
   
   if (fs_edit_state_id != 1)
   {
      $('.FileEditControl').hide();
      $('#EditOn').hide();
   }
   
   $('.ShowFileEditControl').click(function(){
      $('.FileEditControl').toggle();
      $('#EditOn').toggle();
     
      if (fs_edit_state_id == 0)
      {
         $.cookie("fs_edit_state", 1);
      }
      else
      {
         $.cookie("fs_edit_state", 0);
      }
   });
   
   $('#filelisting_OFF_2').tabs({
      selected:filelisting_tab_id,
      show:
         function(event,ui){
            var tab_id2 = ui.index;
            $.cookie("filelisting_tab", tab_id2);
            
            switch(tab_id2)
            {
               case 0:
                  show_FileStoreTree();
               break;
            
               case 1:
                  show_FileStoreSearchPanel();
               break;
            }
         }
   });
   
   function show_FileStoreTree()
   {
      $.ajax({
         cache:false,
         type : 'POST',
         url : ajxscript,
         dataType : 'html',
         data: {
            call : 'file',
            subq : 'show_FileStoreTree',
            oid: $('#FFOID').val(),
            optype : 'table'
         },
         success : function(data){
            $('#FileStoreTreeResult').html(data).show(500);
         },
         error : function(XMLHttpRequest, textStatus, errorThrown) {
            $('#FileStoreTreeResult').html(textStatus).show(500);
         }
      });
		
      return true;
   }
   
   function show_FileStoreSearchPanel()
   {
      $.ajax({
         cache:false,
         type : 'POST',
         url : ajxscript,
         dataType : 'html',
         data: {
            call : 'file',
            subq : 'show_FileStoreSearchPanel',
            oid: $('#FFOID').val(),
            optype : 'table'
         },
         success : function(data){
            $('#FileStoreSearchPanel').html(data).show(500);
         },
         error : function(XMLHttpRequest, textStatus, errorThrown) {
            $('#FileStoreSearchPanel').html(textStatus).show(500);
         }
      });
		
      return true;
   }
   
   $('#FFNameSrch').keyup(function() {
      if ($('#FFNameSrch').val()!='' && $('#FFNameSrch').val()!=' ')
      {
         $('#FileStoreSearchResult').empty();
         $.ajax({
            cache:false,
            type : 'POST',
            url : ajxscript,
            dataType : 'html',
            data: {
               call : 'file',
               subq : 'get_FileSearch_list',
               oid: $('#FFOID').val(),
               ffname: $('#FFNameSrch').val(),
               optype : 'table'
            },
            success : function(data){
               $('#FileStoreSearchPanel').append().html(data).show(500);
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
               $('#FileStoreSearchPanel').hide('blind',500);
               $('#FileStoreSearchPanel').html(textStatus).show('blind',500);
            }
         });
      }
   });
   
   $("#FileStoreTreeJSON")
      .jstree({
         "json_data" : {
            "ajax" : {
            	"url" : fsurl
            }
            ,"progressive_render" : true
         },
         "plugins" : [ "themes", "json_data", "search", "ui", "crrm" ]
      })
      .bind("select_node.jstree", function(event, data){      
         if (data.args[0]['id'].substr(0,9)==='filenode_')
         {
            window.open(data.args[0]['href']);
         }
      })
      .bind("upload.jstree", function(event, data){
         console.log(data);
      });

});