$(document).ready(function()
{
   var add_folder_frm      = $('<form method="POST"><input type="hidden" name="action" value="file"><input type="hidden" name="call" value="addfolder"><input type="text" name="foldername" size="25" maxlength="25"><input type="image" src="images/accept.png"></form>');
   var shw_hidden_files    = parseInt($.cookie("shw_hidden_files")) || 1;
   var fileselect_tab_id   = parseInt($.cookie("fileselect_tab")) || 0;
   var filelisting_tab_id  = parseInt($.cookie("filelisting_tab")) || 0;
   var ajxscript           = 'subs/ajax_file_req.php';
   var ffsearchdialog      = $('<input id="ffnamesrch" type="text" name="ffname" value="Enter Filename Search..." size="40">');
   var resloading          = '<img src="../images/mozilla_blu.gif">';
   //var d_popup_pos         = $('#ContentPanelButton').position();
   
   $.cookie("shw_hidden_files", 1);
   $.cookie("shw_f_perms", 0);
   
   $('#folderdetailstatus').hide('fast');
   $('#filedetailstatus').hide('fast');
   $('#ContentButtonPanel').hide('fast');
   
   $('#FileManagerHeader').addClass('ui-widget ui-widget-content ui-corner-all');
   $('#FolderPanel').addClass('ui-widget ui-widget-content ui-corner-all');
   $('#ContentPanel').addClass('ui-widget ui-widget-content ui-corner-all');
   
   getFolderTree('n');
   
   if ($.cookie("shw_hidden_files") && $.cookie("shw_hidden_files")==1)
   {
      $('#ShowActive').addClass('ui-state-active');
   }
   
   function getFolderTree(shw_hid)
   {
      $.ajax({
         cache:false,
         type : 'POST',
         url : ajxscript,
         dataType : 'html',
         timeout: 5000,
         beforeSend: function() {$('#folderdetailstatus').show('fast')},
         complete: function() {$('#folderdetailstatus').hide('fast')},
         data: {
            call : 'file',
            subq : 'get_FolderTree_list',
            oid: $('#FFOID').val(),
            shwhid: shw_hid,
            optype : 'table'
         },
         success : function(data){
            $('#folderdetailcontent').hide('blind',300);
            $('#folderdetailcontent').append().html(data).show('blind',300);
            $('#file_tree').treeview({
                  collapsed: true,
                  animated: "medium",
                  control:"#treecontrol",
                  //persist: "location",
                  unique: true
            });
         },
         error : function(XMLHttpRequest, textStatus, errorThrown) {
            $('#folderdetailcontent').hide('blind',300);
            $('#folderdetailcontent').html(textStatus).show('blind',300);
         }
      });
   }
   
   function get_FileList(flstid)
   {
      var shw_hidn=parseInt($.cookie("shw_hidden_files"));
      $('#filedetailcontent').empty();
      $.ajax({
         cache:false,
         type : 'POST',
         url : ajxscript,
         dataType : 'html',
         timeout: 5000,
         beforeSend: function() {$('#filedetailstatus').show('fast')},
         complete: function() {$('#filedetailstatus').hide('fast')},
         data: {
            call : 'file',
            subq : 'get_FileDetail_html',
            oid: $('#FFOID').val(),
            fscid: flstid,
            shw_hidn : shw_hidn,
            optype : 'table'
         },
         success : function(data){
            $('#filedetailcontent').append().html(data).show('blind',300);
         },
         error : function(XMLHttpRequest, textStatus, errorThrown) {
            $('#filedetailcontent').html(textStatus).show('blind',500);
         }
      });
      
      $('.FolderAdd').attr('id',flstid);
      
      if ($.cookie("shw_hidden_files")==1 || shw_hidn==1)
      {
         $('#ContentButtonPanel').show('fast');
      }
   }
   
   function get_FileList_json(flstid)
   {
      var shw_hidn=parseInt($.cookie("shw_hidden_files"));
      $('#filedetailcontent').empty();
      $.ajax({
         cache:false,
         type : 'POST',
         url : ajxscript,
         dataType : 'json',
         timeout: 5000,
         beforeSend: function() {$('#filedetailstatus').show('fast')},
         complete: function() {$('#filedetailstatus').hide('fast')},
         data: {
            call : 'file',
            subq : 'get_FileDetail_json',
            oid: $('#FFOID').val(),
            fscid: flstid,
            shw_hidn : shw_hidn
         },
         success : function(data){
            //$('#filedetailcontent').append().html(data).show('blind',300);
            //$('#filedetailcontent').append().parse_FileDetail_JSON(data).show('blind',300);
            parse_FileDetail_JSON(data);
            //alert(data);
         },
         error : function(XMLHttpRequest, textStatus, errorThrown) {
            $('#filedetailcontent').hide('blind',500);
            $('#filedetailcontent').html(textStatus).show('blind',500);
         }
      });
   }
   
   function get_Permissions(flstid)
   {
      //alert(flstid);
      $('#ContentButtonPanel').hide('fast');
      $('#filedetailcontent').empty();
      $.ajax({
         cache:false,
         type : 'POST',
         url : ajxscript,
         dataType : 'html',
         timeout: 5000,
         beforeSend: function() {$('#filedetailstatus').show('fast')},
         complete: function() {$('#filedetailstatus').hide('fast')},
         data: {
            call : 'file',
            subq : 'get_Permissions',
            oid: $('#FFOID').val(),
            fscid: flstid,
            dtype : 'folder'
         },
         success : function(data){
            $('#filedetailcontent').append().html(data).show('blind',300);
            //$('#filedetailcontent').append().parse_Permissions(data).show('blind',300);
         },
         error : function(XMLHttpRequest, textStatus, errorThrown) {
            $('#filedetailcontent').html(textStatus).show('blind',500);
         }
      });
   }

   function get_TrashBin()
   {
      $('#filedetailcontent').empty();
      $.ajax({
         cache:false,
         type : 'POST',
         url : ajxscript,
         dataType : 'html',
         timeout: 5000,
         beforeSend: function() {$('#filedetailstatus').show('fast')},
         complete: function() {$('#filedetailstatus').hide('fast')},
         data: {
            call : 'file',
            subq : 'get_TrashBin',
            oid: $('#FFOID').val(),
            optype : 'table'
         },
         success : function(data){
            $('#filedetailcontent').append().html(data).show('blind',300);
         },
         error : function(XMLHttpRequest, textStatus, errorThrown) {
            $('#filedetailcontent').html(textStatus).show('blind',500);
         }
      });
   }
   
   function parse_Permission(data)
   {
      $.each(data,function(k1,v1){
         alert('Key: ' + k1 + ': Value : ' + v1);
      });
   }
   
   function parse_FileDetail_JSON(data)
   {
      $.each(data,function(k1,v1){
         $.each(v1,function(k2,v2){
            alert('Key: ' + k2 + ': Value : ' + v2);
         });
      });
      
      return;
   }
   
   $('#set_fscid_sec').live('change', function()
   {
      var new_fscid_sec=$(this).val();
      var fscidId=$(this).parent().attr('id');
      //alert('Change ' + fscidId + ' to ' + new_fscid_sec);
      $.ajax({
         cache:false,
         type : 'POST',
         url : ajxscript,
         dataType : 'html',
         timeout: 5000,
         beforeSend: function() {$('#filedetailstatus').show('fast')},
         complete: function() {$('#filedetailstatus').hide('fast')},
         data: {
            call : 'file',
            subq : 'update_Folder_security',
            oid : $('#FFOID').val(),
            fscid : fscidId,
            new_sec : new_fscid_sec
         },
         success : function(data){
            //$('#filedetailcontent').append().html(data).show('blind',300);
            get_Permissions(fscidId);
         },
         error : function(XMLHttpRequest, textStatus, errorThrown) {
            $('#filedetailcontent').html(textStatus).show('blind',500);
         }
      });
   });
   
   $('#FolderTreeList').click(function() {
      getFolderTree('n');
   });
   
   $('.FileListDetail').live('click',function() {
      get_FileList($(this).attr('id'));
   });
   
   $('.ShowPermissions').live('click',function() {
      get_Permissions($(this).attr('id'));
   });
   
   $('#TrashDrop').live('hover',function() {
      $(this).droppable({
         drop: function(event,ui){
            alert('Dropped');
         }
      });
   });
   
   $('.FolderDrag').live('click',function(){
      $(this).draggable({
         revert:true
      });
   });
   
   $('.FileDrag').live('click',function(){
      $(this).draggable({
         revert:true
      });
   });
   
   $('.FolderDrop').droppable({
      activeClass: "ui-state-default",
      hoverClass: "ui-state-hover",
      drop: function(event, ui) {
         //$(this).addClass('ui-state-highlight').find('p').html('Dropped!');
         alert('Dropped');
      }
   });
   
   $('#ShowActive').live('click',function() {
      var shw_hidden_files    = 1
      $.cookie("shw_hidden_files", 1);
      $(this).addClass('ui-state-active');
      $('#ShowInactive').removeClass('ui-state-active');
      $('#list_trash_bin').removeClass('ui-state-active');
      $('#filedetailstatus').hide('fast')
      $('#filedetailcontent').hide('fast')
      getFolderTree('n');
      $('#ContentButtonPanel').hide('fast');
      get_FileList(0);
   });
   
   $('#ShowInactive').live('click',function() {
      var shw_hidden_files    = 0
      $.cookie("shw_hidden_files", 0);
      $(this).addClass('ui-state-active');
      $('#ShowActive').removeClass('ui-state-active');
      $('#list_trash_bin').removeClass('ui-state-active');
      $('#filedetailstatus').hide('fast')
      $('#filedetailcontent').hide('fast')
      getFolderTree('h');
      //$('#ContentButtonPanel').hide('fast');
      get_FileList(0);
      $('#ContentButtonPanel').hide('fast');
   });
   
   $('#list_trash_bin').live('click',function(){
      $(this).addClass('ui-state-active');
      $('#ShowInactive').removeClass('ui-state-active');
      $('#ShowActive').removeClass('ui-state-active');
      get_TrashBin();
      $('#ContentButtonPanel').hide('fast');
   });
   
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
      height: 175,
      width: 250,
      position: 'center',
      modal: true,
      buttons: {
         Add: function() {
            if ($('#newfoldername').val()!='')
            {
               var pfldid = $('#FolderParentID').val();
               $.ajax({
                  cache:false,
                  type : 'POST',
                  url : ajxscript,
                  dataType : 'html',
                  data: {
                     call : 'file',
                     subq : 'add_Folder',
                     oid : $('#FFOID').val(),
                     parentid : pfldid,
                     slevel : $('#set_acl').val(),
                     foldername : $('#newfoldername').val(),
                     longstorage : $('#fldlongstorage').val(),
                     optype : 'table'
                  },
                  success : function(data){
                     $(this).dialog('close');
                     get_FileList(pfldid);
                  },
                  error : function(XMLHttpRequest, textStatus, errorThrown) {
                     $('#folderdetailcontent').hide('blind',300);
                     $('#folderdetailcontent').html(textStatus).show('blind',300);
                  }
               });
               
               $(this).dialog('close');
            }
            else
            {
               alert('Folder name invalid');
            }
         },
         Cancel: function() {
            $(this).dialog('close');
            $('.FolderAdd').removeClass('ui-state-active');
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
            $.ajax({
               cache:false,
               type : 'POST',
               url : ajxscript,
               dataType : 'html',
               data: {
                  call : 'file',
                  subq : 'delete_Folder',
                  oid: fldoid,
                  ParentId: pfldpid,
                  FolderId: fldpid,
                  optype : 'table'
               },
               success : function(data){
                  $('#folderdetailcontent').hide('blind',300);
                  $('#folderdetailcontent').append().html(data).show('blind',300);
                  $(this).dialog('close');
               },
               error : function(XMLHttpRequest, textStatus, errorThrown) {
                  $('#folderdetailcontent').hide('blind',300);
                  $('#folderdetailcontent').html(textStatus).show('blind',300);
               }
            });
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
      height: 175,
      width: 250,
      modal: true,
      position: 'center',
      buttons: {
         Add: function() {
            if ($('#userfile').val()!='')
            {
               var pfldid = $('#FolderParentID').val();
               $.ajax({
                  cache:false,
                  type : 'POST',
                  url : ajxscript,
                  dataType : 'html',
                  data: {
                     call : 'file',
                     subq : 'add_File',
                     oid : $('#nfoid').val(),
                     parentid : pfldid,
                     nfuid: $('#nfuid').val(),
                     nfstoretype: $('#nfstoretype').val(),
                     nfilename : $('#nfuserfile').val(),
                     MAX_FILE_SIZE:$('#MAX_FILE_SIZE').val()
                  },
                  success : function(data){
                     $(this).dialog('close');
                     get_FileList(pfldid);
                  },
                  error : function(XMLHttpRequest, textStatus, errorThrown) {
                     $('#folderdetailcontent').hide('blind',300);
                     $('#folderdetailcontent').html(textStatus).show('blind',300);
                  }
               });
               //$('#AddFileForm').submit();
               $(this).dialog('close');
            }
            else
            {
               alert('Folder name invalid');
            }
         },
         Cancel: function() {
            $(this).dialog('close');
            $('.FileAdd').removeClass('ui-state-active');
         }
      }
   });
   
   $('.FolderAdd').live('click',function() {
     // alert(dialog_popup_pos.top + ' and ' + dialog_popup_pos.left);
      $(this).addClass('ui-state-active');
      $('.FileAdd').removeClass('ui-state-active');
      var flapid= $(this).attr('id');
      var abspos= $(this).offset();
      
      if (flapid=='NA')
      {
         alert('Error: Invalid Folder ID');
      }
      else
      {
         //$('#AddFolderDialog').dialog({position:[abspos.top, abspos.left]});
         $('#AddFolderDialog').dialog('open');
         $('#FolderParentId').unbind();
         $('#FolderParentId').remove();
         $('#DivAddFolderFormElement').append('<input type="hidden" name="parentid" id="FolderParentID" value="' + flapid + '">');
         $('#newfoldername').val('');
         $('#newfoldername').focus();
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
   
   $('.FolderDelete').live('click',function() {
      var fldoid= $('#FFOID').val();
      var fscid= $(this).attr('id');
      var pfscid= $(this).find('div.FolderParent').attr('id');      
      $.ajax({
         cache:false,
         type : 'POST',
         url : ajxscript,
         dataType : 'html',
         data: {
            call : 'file',
            subq : 'delete_Folder',
            oid: fldoid,
            fscid: fscid,
            pfscid: pfscid,
            optype : 'table'
         },
         success : function(data){
            $('#filedetailstatus').hide('blind',300);
            $('#filedetailstatus').append().html(data).show('blind',300);
            $('#filedetailstatus').hide('blind',500);
            
            $('#filedetailcontent').empty();
            $.ajax({
               cache:false,
               type : 'POST',
               url : ajxscript,
               dataType : 'html',
               data: {
                  call : 'file',
                  subq : 'get_FileDetail_list',
                  oid: $('#FFOID').val(),
                  fscid: pfscid,
                  optype : 'table'
               },
               success : function(data){
                  $('#filedetailcontent').append().html(data).show('blind',300);
               },
               error : function(XMLHttpRequest, textStatus, errorThrown) {
                  $('#filedetailcontent').hide('blind',500);
                  $('#filedetailcontent').html(textStatus).show('blind',500);
               }
            });
         },
         error : function(XMLHttpRequest, textStatus, errorThrown) {
            $('#filedetailcontent').hide('blind',300);
            $('#filedetailcontent').html(textStatus).show('blind',300);
         }
      });
   });
   
   $('.FolderRestore').live('click',function() {
      var fldoid= $('#FFOID').val();
      var fscid= $(this).attr('id');
      var pfscid= $(this).find('div.FolderParent').attr('id');
      
      //alert(fscid);
      //alert(pfscid);
      
      $.ajax({
         cache:false,
         type : 'POST',
         url : ajxscript,
         dataType : 'html',
         data: {
            call : 'file',
            subq : 'restore_folder',
            oid: fldoid,
            fscid: fscid,
            pfscid: pfscid,
            optype : 'table'
         },
         success : function(data){
            $('#filedetailstatus').hide('blind',300);
            $('#filedetailstatus').append().html(data).show('blind',300);
            $('#filedetailstatus').hide('blind',500);
            
            $('#filedetailcontent').empty();
            $.ajax({
               cache:false,
               type : 'POST',
               url : ajxscript,
               dataType : 'html',
               data: {
                  call : 'file',
                  subq : 'get_FileDetail_list',
                  oid: $('#FFOID').val(),
                  fscid: pfscid,
                  optype : 'table'
               },
               success : function(data){
                  $('#filedetailcontent').append().html(data).show('blind',300);
               },
               error : function(XMLHttpRequest, textStatus, errorThrown) {
                  $('#filedetailcontent').hide('blind',500);
                  $('#filedetailcontent').html(textStatus).show('blind',500);
               }
            });
         },
         error : function(XMLHttpRequest, textStatus, errorThrown) {
            $('#filedetailcontent').hide('blind',300);
            $('#filedetailcontent').html(textStatus).show('blind',300);
         }
      });
   });
   
   $('.FileAdd').live('click',function() {
      $(this).addClass('ui-state-active');
      $('.FolderAdd').removeClass('ui-state-active');
      var fiapid= $(this).attr('id');
      $('#AddFileDialog').dialog('open');
      $('#FileParentId').unbind();
      $('#FileParentId').remove();
      $('#DivAddFileFormElement').append('<input type="hidden" name="fscid" id="FileParentId" value="' + fiapid + '">');
      $('#userfile').focus();
      //alert($('#FileParentId').val());
   });
   
   $('.FileDelete').live('click',function() {
      var fidpid= $(this).attr('id');
      $('#DeleteFileDialog').dialog('open');
      $('#FileId').unbind();
      $('#FileId').remove();
      $('#DivDeleteFileFormElement').append('<input type="hidden" name="docid" id="FileId" value="' + fidpid + '">');
      //alert('Test');
   });
   
   $('#SharedFilesENT').click(function() {
      getFolderTree('n');
   });
   
   $('#FFNameSrch').keyup(function() {
      if ($('#FFNameSrch').val()!='' && $('#FFNameSrch').val()!=' ')
      {
         $('#filedetailcontent').empty();
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
               //$('#filedetailcontent').hide('blind',500);
               //$('#filedetailcontent').append().html(data).show('blind',500);
               $('#filedetailcontent').append().html(data).show(500);
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
               $('#filedetailcontent').hide('blind',500);
               $('#filedetailcontent').html(textStatus).show('blind',500);
            }
         });
      }
   });
});