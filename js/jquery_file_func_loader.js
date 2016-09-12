$(document).ready(function()
{
   var fileselect_tab_id   = parseInt($.cookie("fileselect_tab")) || 0;
   var filelisting_tab_id  = parseInt($.cookie("filelisting_tab")) || 0;
   var fsoid               = $('#FFOID').val();
   var spinnerIMG	       = '<img src="images/mozilla_blu.gif"> Retrieving...';
   var ajxscript	       = 'subs/ajax_file_req.php';
   var fsurl               = 'subs/ajax_file_req.php?call=file&subq=show_FileStoreTreeJSON&oid=' + fsoid;
   
   $('#file_tree').hide();
   $('.FileEditControl').hide();
   $('#AddFolderDialog').remove();
   $('#DeleteFolderDialog').remove();
   $('#AddFileDialog').remove();
   $('#DeleteFileDialog').remove();
   $('.ShowFileEditControl').remove();
   $('#EditOn').remove();
   
   $('#filelisting').tabs();
   
   $('#fileselect').tabs({
      selected: fileselect_tab_id,
      show: function(event,ui){
         var tab_id1 = ui.index;
         $.cookie("fileselect_tab", tab_id1);
      } 
   });
   
   $('#filelisting_OFF').tabs({
      selected:filelisting_tab_id,
      show: function(event,ui){
         var fltabid = ui.index;
         $.cookie("filelisting_tab", fltabid);
         
         switch(fltabid)
         {
            case 0:
               $('#FileStoreTreeResultHTML').html(spinnerIMG).show(500);
               show_FileStoreTreeHTML();
            break;
         
            case 1:
               $('#FileStoreListResultHTML').html(spinnerIMG).show(500);
               show_FileListHTML();
            break;
         }
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
            alert(textStatus);
         }
      });
		
      return true;
   }
   
   function show_FileListHTML()
   {
      $.ajax({
         cache:false,
         type : 'GET',
         url : ajxscript,
         dataType : 'html',
         data: {
            call : 'file',
            subq : 'show_FileStoreListHTML',
            oid: $('#FFOID').val(),
            optype : 'table'
         },
         success : function(data){
            $('#FileStoreListResultHTML').empty().html(data).show(500);
         },
         error : function(XMLHttpRequest, textStatus, errorThrown) {
            alert(textStatus);
         }
      });
      
      return false;
   }
   
   function show_FileStoreTreeHTML()
   {
      $.ajax({
         cache:false,
         type : 'GET',
         url : ajxscript,
         dataType : 'html',
         data: {
            call : 'file',
            subq : 'show_FileStoreTreeHTML',
            oid: $('#FFOID').val(),
            optype : 'table'
         },
         success : function(data){
            set_FTResult(data);
         },
         error : function(XMLHttpRequest, textStatus, errorThrown) {
            alert(textStatus);
         }
      });
      
      return false;
   }
   
   function set_FTResult(data)
   {
      $('#FileStoreTreeResultHTML').empty().html(data).show(500);
      $('#file_treeHTML').treeview({
         collapsed: true,
         animated: "medium",
         control:"#treecontrol",
         persist: "location"
      }).show();
      $('.FileEditControl').hide();
      $('.folder').hover(function(){
         $(this).parent().children('.FileEditControl').show();
      },function(){
         $(this).parent().children('.FileEditControl').hide();
      });
      
      $('.file').hover(function(){
         $(this).children('.FileEditControl').show();
      },function(){
         $(this).children('.FileEditControl').hide();
      });
      
      $('.FileEditControl').hover(function(){
         $(this).show();
      },function(){
         $(this).hide();
      });
      
      $('.FileAdd').click(function(event) {
         event.preventDefault();
         var tel=$(this);
         OpenAddFileDialog(tel);
      });
      
      $('.FileDelete').click(function(event) {
         event.preventDefault();
         var tel= $(this);
         OpenDeleteFileDialog(tel);
      });
      
      $('.FolderAdd').click(function(event) {
         event.preventDefault();
         var tel=$(this);
         OpenAddFolderDialog(tel);
      });
      
      $('.FolderDelete').click(function(event){
         event.preventDefault();
         
         $('#DeleteFolderForm').remove();
         $('#DeleteFolderDialogNew').remove();
   
         var tel  =$(this);
         var fldpid=parseInt(tel.attr('id'));
         var fcnt =[];
         var nel  ='DeleteFolderDialogNew';
         
         if (!isNaN(fldpid) && fldpid!=0)
         {
            fcnt =  (function(){
               var json = null;
               $.ajax({
                  'async': false,
                  'global': false,
                  'url': ajxscript+'?call=file&subq=get_FSInfo&optype=json&oid='+fsoid+'&fldpid='+fldpid,
                  'dataType': "json",
                  'success': function (data) {
                     json = data;
                  }
               });
            
            return json;
            })();
         }
   
         var dfd  ='';
         dfd+='<form id="DeleteFolderForm" action="http://jms.bhnmi.com/" method="POST">';
         dfd+='<input type="hidden" name="action" value="file">';
         dfd+='<input type="hidden" name="call" value="folder_maintenance">';
         dfd+='<input type="hidden" class="nfscid" name="nfscid[]" value="' + fldpid + '">';
         
         if (!isNaN(fcnt.folder_cnt) && fcnt.folder_cnt > 0)
         {
            dfd+='This Folder contains '+fcnt.folder_cnt+' subfolder(s).<br />';
            
            $.each(fcnt.folder_arr,function(k,v)
            {
               dfd+='<input type="hidden" class="nfscid" name="nfscid[]" value="' + v + '">';
            });
         }
         
         if (!isNaN(fcnt.file_cnt) && fcnt.file_cnt > 0)
         {
            dfd+='This Folder contains '+fcnt.file_cnt+' file(s).<br>';
            
            $.each(fcnt.file_arr,function(k1,v1)
            {
               dfd+='<input type="hidden" class="ndocid" name="ndocid[]" value="'+v1+'"><br />';
            });
         }
         
         dfd+='Confirm Action: <select name="ConfirmFSAction" id="ConfirmFSAction"><option value="Deactivate">Deactivate</option><option value="Delete">Delete</option></select><br />';
         
         $('body').append('<div id="'+nel+'"></div>');      
         DeleteFolderDialog('#'+nel,tel);
         $('#'+nel).append(dfd);
      });
      
      return false;
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
   
   $("#FileStoreTreeJSON1").jstree({
      "json_data" : {
         "ajax" : {
			"url" : fsurl
         }
         ,"progressive_render" : true
	  },
	  //"plugins" : [ "themes", "json_data", "search", "ui", "crrm", "contextmenu" ]
      "plugins" : [ "themes", "json_data", "search", "ui", "crrm" ]
   });
   
   $('#file_treexx').treeview({
      collapsed: true,
      animated: "medium",
	  control:"#treecontrol",
	  persist: "location"
   }).show();
   
   $('#FileStoreTreeJSON').treeview({
      collapsed: true,
      animated: "medium",
	  control:"#treecontrol",
	  persist: "location"
   }).show();
   
   function OpenAddFolderDialog(el)
   {
      var fldpid=el.attr('id');
      var foid=$('#FFOID').val();
      var tsid=$('#FFSID').val();
      
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
                  of: el
            },
            buttons: {
               Add: function() {
                  if ($('#newfoldername').val()!='')
                  {
                     submitAddFolder($('#FolderParentId').val(),$('#newfoldername').val());
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
   
   function submitAddFolder(parentid,foldername)
   {
      $.ajax({
         cache:false,
         type : 'POST',
         url : ajxscript,
         dataType : 'html',
         timeout: 5000,
         data: {
            call : 'file',
            subq : 'add_Folder',
            oid: $('#FFOID').val(),
            parentid: parentid,
            foldername : foldername,
            optype : 'table'
         },
         success : function(data){
            $('#FileStoreTreeResultHTML').html(spinnerIMG).show(500);
            show_FileStoreTreeHTML();
         },
         error : function(XMLHttpRequest, textStatus, errorThrown) {
            alert(textStatus);
         }
      });
      
      return false;
   }
   
   function submitDeleteFolder()
   {
      //alert($('#nfscid').val());
      nfscidar = new Array;
      $('input.nfscid').each(function(id) { 
         myVar1 = $('input.nfscid').get(id); 
         nfscidar.push(myVar1.value);
      });
      
      ndocidar = new Array;
      $('input.ndocid').each(function(id) { 
         myVar2 = $('input.ndocid').get(id); 
         ndocidar.push(myVar2.value);
      });
      
      $.ajax({
         cache:false,
         type : 'POST',
         url : ajxscript,
         dataType : 'html',
         timeout: 5000,
         data: {
            call : 'file',
            subq : 'folder_maintenance',
            oid: $('#FFOID').val(),
            nfscid: nfscidar,
            ndocid: ndocidar,
            ConfirmFSAction: $('#ConfirmFSAction').val(),
            optype : 'table'
         },
         success : function(data){
            $('#FileStoreTreeResultHTML').html(spinnerIMG).show(500);
            show_FileStoreTreeHTML();
         },
         error : function(XMLHttpRequest, textStatus, errorThrown) {
            alert(textStatus);
         }
      });

      return false;
   }
   
   function submitDeleteFile()
   {
      $.ajax({
         cache:false,
         type : 'POST',
         url : ajxscript,
         dataType : 'html',
         timeout: 5000,
         data: {
            call : 'file',
            subq : 'file_maintenance',
            oid: $('#FFOID').val(),
            docid: $('#FileId').val(),
            ConfirmFSAction: $('#ConfirmFSAction').val(),
            optype : 'table'
         },
         success : function(data){
            $('#FileStoreTreeResultHTML').html(spinnerIMG).show(500);
            show_FileStoreTreeHTML();
         },
         error : function(XMLHttpRequest, textStatus, errorThrown) {
            alert(textStatus);
         }
      });
      return false;
   }
   
   function OpenAddFileDialog(el)
   {
      var fiapid= el.attr('id');
      var foid=$('#FFOID').val();
      var fsid=$('#FFSID').val();
      $('#AddFileDialog').remove();
      $('body').append('<div id="AddFileDialog"></div>');
      
      var aff='';
      aff+='Select File for upload<br>';
      aff+='<form id="AddFileForm" enctype="multipart/form-data" method="post">';
      aff+='<input type="hidden" name="action" value="file">';
      aff+='<input type="hidden" name="call" value="upload_file_OFF">';
      aff+='<div id="DivAddFileFormElement"><input type="hidden" name="fscid" id="FileParentId" value="' + fiapid + '"></div>';
      aff+='<input type="hidden" name="storetype" value="file">';
      aff+='<input type="hidden" name="foid" value="'+foid+'">';
      aff+='<input class="buttondkgraypnl" type="file" name="userfile[]" id="userfile">';
      aff+='</form>';
      
      $('#AddFileDialog').append(aff);
      
      var dialog =
         $('#AddFileDialog').dialog({
            title: 'Upload File',
			bgiframe: true,
			autoOpen: false,
            resizeable: false,
			height: 150,
            width: 250,
			modal: true,
            close: CloseAddFileDialog(),
            position: {
                  my: 'left top',
                  at: 'right top',
                  of: el
            },
            buttons: {
               Add: function() {
                  var uf=$('#userfile').val();
                  if (uf.length > 0) {
                     $('#AddFileForm').submit();
                     CloseAddFileDialog();
                  }
                  else {
                     alert('File name invalid');
                  }
			   },
               Cancel: function() {
                  CloseAddFileDialog();
			   }
            }
         }).dialog('open');
   }
   
   function CloseAddFileDialog()
   {
      $('#AddFileDialog').dialog('close');
      $('#AddFileDialog').remove();
      return false;
   }
   
   function CloseDeleteFolderDialog()
   {
      $('#DeleteFolderForm').remove();
      $('#DeleteFolderDialogNew').remove();
      return false;
   }
   
   function DeleteFolderDialog(el,posel)
   {
      var dialog=
      $(el).dialog({
            title: 'Folder Maintenance',
			bgiframe: true,
			autoOpen: false,
            resizeable: false,
			height: 200,
            width: 250,
			modal: true,
            position: {
                  my: 'left top',
                  at: 'right top',
                  of: posel
            },
            close: CloseDeleteFolderDialog(),
            buttons: {
               Submit: function() {
                  procFolderMaintenance();
			   },
               Cancel: function() {
                  CloseDeleteFolderDialog();
			   }
            }
	  }).dialog('open');
      
      return false;
   }
   
   function procFolderMaintenance()
   {
      var fsid= $('#FFSID').val();
      var cfd = $('#ConfirmFSAction').val();
      
      if (cfd=='Delete')
      {
         var tcon=confirm('Warning!\nOnce these Folders/Files are deleted they are gone forever.\nClick Ok to Continue.');
         
         if (tcon)
         {
            submitDeleteFolder();
            CloseDeleteFolderDialog();
         }
      }
      else
      {
         submitDeleteFolder();
         CloseDeleteFolderDialog();
      }

      return false;
   }
   
   function OpenDeleteFileDialog(el)
   {
      var fidpid=el.attr('id');
      var foid=$('#FFOID').val();
      var fsid=$('#FFSID').val();
      var dfd='';
      dfd+='<form id="DeleteFileForm" method="post">';
      dfd+='<input type="hidden" name="action" value="file">';
      dfd+='<input type="hidden" name="call" value="file_maintenance">';
      dfd+='<div id="DivDeleteFileFormElement"><input type="hidden" name="docid" id="FileId" value="' + fidpid + '"></div>';
      dfd+='Confirm Action: <select name="ConfirmFSAction" id="ConfirmFSAction"><option value="Deactivate">Deactivate</option><option value="Delete">Delete</option></select>';
      dfd+='</form>';
      
      $('#DeleteFileDialog').remove();
      $('body').append('<div id="DeleteFileDialog"></div>');      
      $('#DeleteFileDialog').append(dfd);   
   
      var dialog =
      $('#DeleteFileDialog').dialog({
            title: 'File Maintenance',
			bgiframe: true,
			autoOpen: false,
            resizeable: false,
			height: 150,
            width: 250,
			modal: true,
            close: CloseDeleteFileDialog(),
            position: {
                  my: 'left top',
                  at: 'right top',
                  of: el
            },
            buttons: {
               Submit: function() {
                  submitDeleteFile();
                  CloseDeleteFileDialog();
			   },
               Cancel: function() {
                  CloseDeleteFileDialog();
			   }
            }
	  }).dialog('open');
   }
   
   function CloseDeleteFileDialog()
   {
      $('#DeleteFileDialog').dialog('close');
      $('#DeleteFileDialog').remove();
      return false;
   }
});