$(document).ready(function()
{

   var AddFolder           = $('<form method="POST"><input type="hidden" name="action" value="file"><input type="hidden" name="call" value="addfolder"><input type="text" name="foldername" size="25" maxlength="25"><input type="image" src="images/accept.png"></form>');
   var fs_edit_state_id    = parseInt($.cookie("fs_edit_state")) || 0;
   var fileselect_tab_id   = parseInt($.cookie("fileselect_tab")) || 0;
   var filelisting_tab_id  = parseInt($.cookie("filelisting_tab")) || 0;
   
   var filelisting_tab_id2n= parseInt($.cookie("filelisting_tab2")) || 0;
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
      selected:filelisting_tab_id2n,
      show:    function(event,ui){
                  var tab_id2n = ui.index;
                  $.cookie("filelisting_tab2", tab_id2n);
                  
                  switch(tab_id2n)
                  {
                     //case 0:
                     //   show_FileStoreTree();
                     //break;
                  
                     case 1:
                        show_FileStoreSearchPanel();
                     break;
                     
                     case 2:
                        show_FileStoreCapacity();
                     break;
                  }
      }
   });
   
   function show_FileStoreTree_OLD()
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
            return $("<img/>").attr("src", this.src);
         },
         top: 10,
         left:-50
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
   
   $('#FileStoreTreeJSON').jstree({
      'json_data' : {
         'ajax' : {
            'url' :fsurl
         },
         'progressive_render' :true
      },
      'contextmenu': {
            'items': {
               'ccp': false,
               'create': {
                  'label': "Create Folder",
                  'action': function (e){
                     var tnode=e.children('li > a').attr('id');
                     if (tnode.substr(0,9)==='foldnode_')
                     {
                        OpenAddFolderDialog(parseInt(tnode.substr(9)));
                     }
                  }
               },
               'remove': {
                  'label': "Delete",
                  'action': function (e){
                     var tnode=e.children('li > a').attr('id');
                     if (tnode.substr(0,9)==='foldnode_')
                     {
                        if (confirm('Are you sure you want to delete this Folder?\nAll subfolders and files will be deleted as well.\n\nClick OK to Continue.')) {
                           //console.log('Delete Folder: '+parseInt(tnode.substr(9)));
                        }
                     }
                     
                     if (tnode.substr(0,9)==='filenode_')
                     {
                        if (confirm('Are you sure you want to delete this File?\nThis action is premanent, file cannot be restored.\n\nClick OK to Continue.')) {
                           console.log('Delete File: '+parseInt(tnode.substr(9)));
                           $('#FileStoreTreeJSON').jstree('refresh',-1);
                        }
                     }
                  }
               },
               'rename': false,
               'upload': {
                  'label': "Upload File",
                  'action': function (e){
                     var tnode=e.children('li > a').attr('id');
                     if (tnode.substr(0,9)==='foldnode_')
                     {
                        console.log('Upload File: '+parseInt(tnode.substr(9)));
                        $('#FileStoreTreeJSON').jstree('refresh',-1);
                     }
                  }
               }
            } // end items
      },
      "plugins" : [ "themes", "json_data", "search", "ui", "crrm", "contextmenu" ]
   })
   .bind("select_node.jstree", function(event, data){
      if (data.args[0]['id'].substr(0,9)==='filenode_')
      {
         window.open(data.args[0]['href']);
      }
   });
   
   function OpenAddFolderDialog(el)
   {
      var fldpid=el;
      var foid = $('FFOID').val();
      $('#AddFolderDialog').remove();
      $('body').append('<div id="AddFolderDialog"></div>');
      
      var aff='';
      aff+='Folder Name<br>';
      aff+='<form id="AddFolderForm" method="POST">';
      aff+='<input type="hidden" name="action" value="file">';
      aff+='<input type="hidden" name="call" value="add_folder_OFF">';
      aff+='<div id="DivAddFolderFormElement"></div>';
      aff+='<input type="hidden" name="parentid" id="FolderParentId" value="' + fldpid + '">';
      aff+='<input type="text" name="foldername" id="newfoldername" size="25" maxlength="25">';
      aff+='</form>';
   
      $('#AddFolderDialog').append(aff);
      
      var dialog =
         $('#AddFolderDialog').dialog({
            title: 'Add New Folder',
			bgiframe: true,
			autoOpen: false,
            resizeable: false,
			height: 150,
            width: 250,
			modal: true,
            close: $(this).dialog('close'),
            position: {
                  my: 'left top',
                  at: 'right top',
                  of: $('#foldnode_'+el)
            },
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
         }).dialog('open');
   }
   
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
      $('#FolderParentId').unbind();
      $('#FolderParentId').remove();
      $('#DivAddFolderFormElement').append('<input type="hidden" name="parentid" id="FolderParentId" value="' + flapid + '">');
         //alert(tpid);
   });
   
   $('.FolderDelete').click(function() {
      var fldpid= $(this).attr('id');
	  $('#DeleteFolderDialog').dialog('open');
      $('#FolderId').unbind();
      $('#FolderId').remove();
      $('#DivDeleteFolderFormElement').append('<input type="hidden" name="folderid" id="FolderId" value="' + fldpid + '">');
      //alert('Test');
   });
   
   $('.FileAdd').click(function() {
      var fiapid= $(this).attr('id');
      $('#AddFileDialog').dialog('open');
      $('#FileParentId').unbind();
      $('#FileParentId').remove();
      $('#DivAddFileFormElement').append('<input type="hidden" name="fscid" id="FileParentId" value="' + fiapid + '">');
      //alert('Test');
   });
   
   $('.FileDelete').click(function() {
      var fidpid= $(this).attr('id');
      $('#DeleteFileDialog').dialog('open');
      $('#FileId').unbind();
      $('#FileId').remove();
      $('#DivDeleteFileFormElement').append('<input type="hidden" name="docid" id="FileId" value="' + fidpid + '">');
      //alert('Test');
   });

});