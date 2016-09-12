$(document).ready(function() {
   var AddFolder           = $('<form method="POST"><input type="hidden" name="action" value="file"><input type="hidden" name="call" value="addfolder"><input type="text" name="foldername" size="25" maxlength="25"><input type="image" src="images/accept.png"></form>');
   var fs_edit_state_id    = parseInt($.cookie("fs_edit_state")) || 0;
   var fileselect_tab_id   = parseInt($.cookie("fileselect_tab")) || 0;
   var filelisting_tab_id  = parseInt($.cookie("filelisting_tab")) || 0;
   var fsoid               = $('#FFOID').val();
   var ajxscript	       = 'subs/ajax_file_req.php';
   var fsurl               = '/subs/ajax_file_req.php?call=file&subq=show_FileStoreTreeJSON&oid=' + fsoid;
   
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
   
   $('#filelisting').tabs();
   
   $('#fileselect').tabs({
      selected: fileselect_tab_id,
      show:     function(event,ui){
                  var tab_id1 = ui.index;
                  $.cookie("fileselect_tab", tab_id1);
            } 
  });
   
   $('#filelisting_OFF').tabs({
      selected:filelisting_tab_id,
      show:    function(event,ui){
                  var tab_id2 = ui.index;
                  $.cookie("filelisting_tab", tab_id2);
      }
   });
   
   $('#filelisting_OFF_2').tabs({
      selected:filelisting_tab_id,
      show:    function(event,ui){
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
            
            case 2:
               show_FileStoreCapacity();
            break;
         }
      }
   });
   
   $('.EmailFile').live('click',function(e){
      e.preventDefault();
      var bd=$(this).attr('id');
      var docid=bd.split("_");
      //alert(docid[1]);
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
   
   function show_FileStoreCapacity()
   {
      $.ajax({
         cache:false,
         type : 'POST',
         url : ajxscript,
         dataType : 'html',
         data: {
            call : 'file',
            subq : 'show_FileStoreCapacity',
            oid: $('#FFOID').val(),
            optype : 'table'
         },
         success : function(data){
            $('#FileStoreCapacityResult').html(data).show(500);
         },
         error : function(XMLHttpRequest, textStatus, errorThrown) {
            $('#FileStoreCapacityResult').html(textStatus).show(500);
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
               $('#FileStoreSearchResult').append().html(data).show(500);
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
               $('#FileStoreSearchResult').hide('blind',500);
               $('#FileStoreSearchResult').html(textStatus).show('blind',500);
            }
         });
      }
   });
   
   $('.JMSimgtooltip')
      .tooltip({ 
         delay: 0,
         showURL: false,
         bodyHandler: function()
         { 
            return $("<img/>").attr("src", this.src).attr("width", 320).attr("height", 240);
         },
         top: 25,
         left: -150
      });
   
   $('#errortext').dialog({
      autoOpen: true,
     draggable: false,
      bgiframe: true,
      height: 200,
      modal: true
   });   
   
   $("#fTreeSearchIcon").click(function () {
      $("#FileStoreTreeJSON").jstree("search",$('#fTreeSearch').val());
   });
   
   $("#FileStoreTreeJSON").jstree({
      "json_data" : {
         "ajax" : {
			"url" : fsurl
         }
         ,"progressive_render" : true
	  },
	  //"plugins" : [ "themes", "json_data", "search", "ui", "crrm", "contextmenu" ]
      "plugins" : [ "themes", "json_data", "search", "ui", "crrm" ]
   });
   
   $('#file_tree').treeview({
      collapsed: true,
      animated: "medium",
	  control:"#treecontrol",
	  persist: "location"
   });

   $('#AddFolderDialog').dialog({
			bgiframe: true,
			autoOpen: false,
            resizeable: false,
			height: 150,
            width: 250,
			modal: true,
            buttons: {
               Add: function() {
                  if ($('#newfoldername').val()!='')
                  {
                     $('#AddFolderForm').submit();
                     $(this).dialog('close');
                  }
                  else
                  {
                     alert('Folder name invalid');
                  }
			   },
               Cancel: function() {
				$(this).dialog('close');
			   }
            }
		});
   
   $('#DeleteFolderDialog').dialog({
			bgiframe: true,
			autoOpen: false,
            resizeable: false,
			height: 150,
            width: 250,
			modal: true,
            buttons: {
               Delete: function() {
				$('#DeleteFolderForm').submit();
                $(this).dialog('close');
			   },
               Cancel: function() {
				$(this).dialog('close');
			   }
            }
		});
   
   $('#AddFileDialog').dialog({
			bgiframe: true,
			autoOpen: false,
            resizeable: false,
			height: 150,
            width: 250,
			modal: true,
            buttons: {
               Add: function() {
                  if ($('#userfile').val()!='')
                  {
                     $('#AddFileForm').submit();
                     $(this).dialog('close');
                  }
                  else
                  {
                     alert('Folder name invalid');
                  }
			   },
               Cancel: function() {
                  $(this).dialog('close');
			   }
            }
		});
   
   $('#DeleteFileDialog').dialog({
			bgiframe: true,
			autoOpen: false,
            resizeable: false,
			height: 150,
            width: 250,
			modal: true,
            buttons: {
               Delete: function() {
                  $('#DeleteFileForm').submit();
                  $(this).dialog('close');
			   },
               Cancel: function() {
                  $(this).dialog('close');
			   }
            }
		});
   
   $('.FolderAdd').click(function() {
      var flapid= $(this).attr('id');
      $('#AddFolderDialog').dialog('open');
      $('#FolderParentId').unbind().remove();
      $('#DivAddFolderFormElement').append('<input type="hidden" name="parentid" id="FolderParentId" value="' + flapid + '">');
   });
   
   $('.FolderDelete').click(function() {
      var fldpid= $(this).attr('id');
	  $('#DeleteFolderDialog').dialog('open');
      $('#FolderId').unbind().remove();
      $('#DivDeleteFolderFormElement').append('<input type="hidden" name="folderid" id="FolderId" value="' + fldpid + '">');
   });
   
   $('.FileAdd').click(function() {
      var fiapid= $(this).attr('id');
      $('#AddFileDialog').dialog('open');
      $('#FileParentId').unbind().remove();
      $('#DivAddFileFormElement').append('<input type="hidden" name="fscid" id="FileParentId" value="' + fiapid + '">');
   });
   
   $('.FileDelete').click(function() {
      var fidpid= $(this).attr('id');
      $('#DeleteFileDialog').dialog('open');
      $('#FileId').unbind().remove();
      $('#DivDeleteFileFormElement').append('<input type="hidden" name="docid" id="FileId" value="' + fidpid + '">');
   });

});