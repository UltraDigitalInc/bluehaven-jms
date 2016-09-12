$(document).ready(function() {
   var ajxscript  = 'subs/ajax_changelog_req.php';
   var spinner    = 'Retrieving...';
   
   show_ChangeLogFrame('#ChangeLog');
   
   $('.setpointer').live('hover',function() {
         $(this).css('cursor','pointer');
      },
      function() {
         $(this).css('cursor','auto');
      return false;
   });
   
   $('#procChangeLogList').live('click',function(){
      show_ChangeLogList('#ChangeLogListContainer');
   });
   
   $('#procChangeLogAdd').live('click',function(){
      AddChangeRequest();
   });
   
   $('#cltype').live('change',function(){
      show_ChangeLogList('#ChangeLogListContainer');
   });
   
   $('.procChangeLogAddComment').live('click',function(event){
      event.preventDefault();
      var el='ChangeLogCommentDialog';
      var prnt=$(this).parent().parent();
      var clid=parseInt(prnt.children('.clid').html());
      var ttm=prnt.children('td').children('.cldescription');
      var ctm=prnt.children('td').children('.cltitle').html();
      
      $('.cldescription').hide();
      if (ttm.is(':hidden'))
      {
         ttm.show();
      }
      
      $('#'+el).remove();
      $('body').append('<div id="'+el+'"></div>');
      AddCommentDialog('#'+el,$(this),clid,ctm);
   });
   
   $('#showChangeLogAdd').live('click',function(event){
      event.preventDefault();
      var el='ChangeFormAddDialog';
      
      $('#'+el).remove();
      $('body').append('<div id="'+el+'"></div>');
      AddCLDialog('#'+el);
      return false;
   });
   
   $('.showcldescription').live('click',function(){
      var prnt=$(this).parent().parent();
      
      var clid=parseInt(prnt.children('.clid').html());
      var ttm=prnt.children('td').children('.cldescription');
      
      $('.cldescription').hide();
      if (ttm.is(':hidden'))
      {
         ttm.show();
      }
      
      get_ChangeLogComment(ttm,clid);
      return false;
   });
   
   $('#ExpandAllComments').live('click',function() {
      $('.cldescription').hide();
      $('.cldescription').show();
   });
   
   function get_ChangeLogComment(el,clid) {
      if (el!=null && !isNaN(clid)) {
         $.ajax({
            cache:false,
            type : 'GET',
            url : ajxscript,
            dataType : 'json',
            data: {
               call : 'get_ChangeLogComment',
               clid : clid,
               optype : 'json'
            },
            success : function(data){
               if (data!=null)
               {
                  show_ChangeLogComments(el,data);
               }
            }
         });
      }
      return false;
   }
   
   function show_ChangeLogComments(el,data)
   {
      $('.clcomments').remove();
      el.append('<div class="clcomments"><br><b>Comments</b><br></div>');
      var cms=el.children('.clcomments');
      
      $.each(data.cmnts,function(k,v){
         $(cms).append('<div class="commentdetail"><div class="commentdetailhdr">'+v['adate']+' - '+ v['clowner'] +'</div>'+ v['ctext'] +'</div><br>');
      });
      return false;
   }
   
   function AddCommentDialog(el,fel,clid,ctm) {
      show_AddCommentForm(el,ctm);
      var dialog =
      $(el).dialog({
         title: "Add Change Request Comment",
         bgiframe: true,
         modal: true,
         width: 500,
         draggable: false,
         autoOpen: false,
         resizable: false,
         position: {
                  my: 'right top',
                  at: 'right bottom',
                  of: fel
            },
         close: function() {
            CloseAddCommentDialog();
         },
         buttons: {
            'Submit': function(){
               AddChangeComment(clid);
               get_ChangeLogComment(el,clid);
               CloseAddCommentDialog();
            }
         }
      })
      .dialog('open');
   }
   
   function CloseAddCommentDialog()
   {
      $('#ChangeLogCommentDialog').empty().remove();
      return false;
   }
   
   function show_AddCommentForm(pel,ctm)
   {
      $(pel).append('Change Log Title: '+ ctm +'<br>');
      $(pel).append('<textarea id="clc" rows="4" cols="65" required></textarea><br>');
      return false;
   }
   
   function AddCLDialog(el)
   {
      show_ChangeLogAddForm(el);
      var dialog =
      $(el).dialog({
         title: "Add Change Request",
         bgiframe: true,
         modal: true,
         width: 500,
         draggable: false,
         autoOpen: false,
         resizable: false,
         position: {
                  my: 'right top',
                  at: 'right bottom',
                  of: $('#showChangeLogAdd')
            },
         close: function() {
            CloseAddCLDialog();
         },
         buttons: {
            'Submit': function(){
               AddChangeRequest();
               CloseAddCLDialog();
            }
         }
      })
      .dialog('open');
   }
   
   function CloseAddCLDialog()
   {
      $('#ChangeFormAddDialog').empty().remove();
   }
   
   $('.flagreview').live('click',function(){
      var prnt=$(this).parent().parent();
      var clid=parseInt(prnt.children('.clid').html());
      
      if (!isNaN(clid)) {
         var tconf = confirm ('You are about to mark this Change Request as REVIEWED.\nClick Ok to continue.\n');
         
         if (tconf) {
            $.ajax({
               cache:false,
               type : 'POST',
               url : ajxscript,
               dataType : 'json',
               data: {
                  call : 'set_Reviewed',
                  clid : clid,
                  optype : 'json'
               },
               success : function(data){
                  if (data==null || data.clid == 0) {
                     alert('Submission Error');
                  }
                  else {
                     show_ChangeLogList('#ChangeLogListContainer');
                  }
               }
            });
         }
      }

      return false;
   });
   
   $('.flagcomplete').live('click',function(){
      var prnt=$(this).parent().parent();
      var clid=parseInt(prnt.children('.clid').html());
      
      if (!isNaN(clid))
      {
         var tconf = confirm ('You are about to mark this Change Request as COMPLETE.\nClick Ok to continue.\n');
         
         if (tconf)
         {
            $.ajax({
               cache:false,
               type : 'POST',
               url : ajxscript,
               dataType : 'json',
               data: {
                  call : 'set_Completed',
                  clid : clid,
                  optype : 'json'
               },
               success : function(data){
                  if (data==null || data.clid == 0)
                  {
                     alert('Submission Error');
                  }
                  else
                  {
                     show_ChangeLogList('#ChangeLogListContainer');
                  }
               }
            });
         }
      }
      
      return false;
   });
   
   function AddChangeRequest() {
      var clt = $('#icltype').val();
      var mid = $('#nmid').val();
      var nct = $('#nct').val();
      var ncd = $('#ncd').val();
      var nca = ($('#nca').is(":checked"))?$('#nca').val():0;
      var ncc = ($('#ncc').is(":checked"))?$('#ncc').val():0;
      
      if (nct.length==0 || ncd.length==0) {
         alert('Please fill all Change Request fields.')
      }
      else {
         $('#ChangeLogStatus').empty().html('Processing...');
         
         $.ajax({
            cache:false,
            type : 'POST',
            url : ajxscript,
            dataType : 'json',
            data: {
               call : 'Add_ChangeLogRequest',
               mid : mid,
               clt : clt,
               nct : nct,
               ncd : ncd,
               nca : nca,
               ncc : ncc,
               optype : 'json'
            },
            success : function(data){
               if (data==null || data.clid == 0) {
                  alert('Submission Error: Change Request did not save.');
               }
               else {
                  if (data.mailsent==1) {
                     $('#ChangeLogStatus').empty().html('Email Sent!').hide(800);
                  }
                  
                  show_ChangeLogList('#ChangeLogListContainer');
               }
            }
         });
      }
   }
   
   function AddChangeComment(clid)
   {
      if (!isNaN(clid))
      {
         var clc = $('#clc').val();
         var clt = $('#cltype').val();
         
         if (clc.length==0) {
            alert('Please enter a Comment before submitting')
         }
         else {
            $.ajax({
               cache:false,
               type : 'POST',
               url : ajxscript,
               dataType : 'json',
               data: {
                  call : 'Add_ChangeLogComment',
                  clid : clid,
                  clc : clc,
                  clt : clt,
                  optype : 'json'
               },
               success : function(data){
                  if (data==null || data.clcid == 0)
                  {
                     alert('Submission Error: Comment did not save.');
                  }
                  else
                  {
                     if (data.mailsent==1) {
                        $('#ChangeLogStatus').empty().html('Email Sent!').hide(800);
                     }
                     show_ChangeLogList('#ChangeLogListContainer');
                  }
               }
            });
         }
      }
   }
   
   function show_ChangeLogFrame(pel)
   {
      $(pel).append('<table width="950px" id="ChangeLogContainer"></table>')
      $('#ChangeLogContainer').append('<tr><td align="left"><b>Change Log</b></td><td align="right" id="ChangeLogMenuContainer"></td></tr>')
      $('#ChangeLogContainer').append('<tr><td colspan="2" valign="top" align="left" id="ChangeLogListContainer"></td></tr>')
      
      show_ChangeLogMenu('#ChangeLogMenuContainer');
      show_ChangeLogList('#ChangeLogListContainer');
      return false;
   }
   
   function show_ChangeLogMenu(pel)
   {
      $(pel).append('<table id="ChangeLogMenu"><tr><td><span id="ChangeLogStatus"></span><td>Show Completed <input id="ChangeLogInActive" type="checkbox" value="1"></span></td></td><td> Type <select id="cltype"><option value="M">Maintenance</option><option value="P">Projects</option><option value="B" SELECTED>Both</option></select></td><td><button id="procChangeLogList">List</button></td><td><button id="showChangeLogAdd">Add</button></td></tr></table>');
      $('button').button();
      return false;
   }
   
   function buildSelect(elid,arr)
   {
      var out='';
      
      out+='<select id="'+elid+'">';
      
      $.each(arr,function(k,v){
         out+='<option value="'+k+'">'+v+'</option>';
      });
      
      out+='</select>';
      
      return out;
   }
   
   function buildTable(arr) {
      var i=0;
      var rbg='';
      var tbl='<table id="ChangeLogListTable" class="outer tablesorter" width="945px">';
      tbl+='<thead><tr><th>Date</th><th>Change Title</th><th>Type</th><th>Module</th><th>System Version</th><th>ID</th><th>Owner</th><th>Reviewed</th><th>Completed</th><th></th><th align="center"><img class="setpointer" id="ExpandAllComments" src="images/arrow_out.png"></th></tr></thead><tbody>';
      
      $.each(arr.data,function(k,v){
         if(i % 2 == 0)
         {
            rbg='even';
         }
         else
         {
            rbg='odd';
         }
         
         tbl+='<tr>';
         tbl+='   <td width="100px">'+v['adate']+'</td>';
         tbl+='   <td><div class="cltitle">'+v['cltitle']+'</div><div class="cldescription">'+v['cldescription']+'</div></td>';
         tbl+='   <td align="center">'+v['cltype']+'</td>';
         tbl+='   <td align="center">'+v['modulename']+'</td>';
         tbl+='   <td align="center">'+v['sysversion']+'</td>';
         tbl+='   <td align="center" class="clid">'+v['clid']+'</td>';
         tbl+='   <td align="center">'+v['clowner']+'</td>';
         tbl+='   <td align="center">';
         
         if (v['reviewer']!=null)
         {
            tbl+=v['reviewer']+' - '+v['rdate'];
         }
         else
         {
            tbl+='<a href="#" class="flagreview"><img src="images/pencil_add.png" title="Flag Reviewed"></a>';
         }
         
         tbl+='</td>';
         tbl+='   <td align="center">';
         
         if (v['completer']!=null)
         {
            tbl+=v['completer']+' - '+v['cdate'];
         }
         else
         {
            tbl+='<a href="#" class="flagcomplete"><img src="images/pencil_add.png" title="Flag Completed"></a>';
         }
         
         tbl+='   </td>';
         tbl+='   <td align="center"><a href="#" class="procChangeLogAddComment"><img src="images/comment_add.png" title="Add Comment"></a></td>';
         tbl+='   <td><a href="#" class="showcldescription"><img src="images/bullet_arrow_down.png" title="Show Detail"></a></td>';
         tbl+='</tr>';
         i++;
      });
      
      tbl+='</tbody></table>';
      return tbl;
   }
   
   function show_ChangeLogList(pel) {
      var sct=($('#ChangeLogInActive:checked').length === 1)?1:0;
      var clt=$('#cltype').val();
      arr =  (function(){
         var json = null;
         $.ajax({
            'async': false,
            'global': false,
            'url': ajxscript+'?call=get_ChangeLog&optype=json&cltype='+clt+'&showcompleted='+sct+'&intrvl=30',
            'dataType': "json",
            'success': function (data) {
               json = data;
            }
         });
         
         return json;
      })();
      
      $(pel).empty().append(buildTable(arr))
      //$('#ExpandAllComments').unbind('click');
      $('.cldescription').unbind('click').hide();
      
      $("#ChangeLogListTable").tablesorter({
         widgets: ['zebra'],
         headers:{
            9:{sorter: false},
            10:{sorter: false}
         }
      });
      return false;
   }
   
   function show_ChangeLogAddForm(pel) {
      var lel = 'ChangeLogAddForm';
      var frm = '#'+lel;
      
      var clmodulesJSON =  (function(){
         var json = null;
         $.ajax({
            'async': false,
            'global': false,
            'url': ajxscript+'?call=get_Modules&optype=json',
            'dataType': "json",
            'success': function (data) {
               json = data;
            }
         });
         
         return json;
      })();
      
      $(frm).remove();
      
      $(pel).append('<table width="150px" id="'+ lel +'" align="left"></table>');
      $(frm).append('<tr><td align="right">Type:</td><td align="left"><select id="icltype"><option value="M">Maintenance</option><option value="P">Project</option></select></td></tr>');
      $(frm).append('<tr><td align="right">Module:</td><td align="left">' + buildSelect('nmid',clmodulesJSON)+'</td></tr>');
      $(frm).append('<tr><td align="right">Title:</td><td align="left"><input id="nct" size="28"></td></tr>');
      $(frm).append('<tr><td align="right" valign="top">Detail:</td><td align="left"><textarea id="ncd" rows="4" cols="65" required></textarea></td></tr>');
      $(frm).append('<tr><td align="right" valign="top">Email:</td><td align="left"><input type="checkbox" id="nca" value="1" CHECKED> Send Notification Email</td></tr>');
      $('button').button();

      return false;
   }   
});
   