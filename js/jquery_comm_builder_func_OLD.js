$(document).ready(function()
{
    var cbp_view_tab_id = parseInt($.cookie("cbp_view_menu_tab")) || 0;
    var active_office= parseInt($('#active_office').val()) || 0;
    var active_salesr= parseInt($('#active_salesr').val()) || 0;
    var usrscript   = 'subs/ajax_user_req.php';
    var cbpscript   = 'subs/ajax_cbp_req.php';
    var spinnerIMG  = '<img src="images/mozilla_blu.gif"> Retrieving...';
	var updateTEXT  = '<em>Updating....</em>';
    var updateHTML  = '<img src="images/mozilla_blu.gif"> Updating...';
    var processIMG	= '<img src="images/mozilla_blu.gif"> Processing...';
    
    var cbws = CommBuildWorkSheet();
    
    var cbtJSON =  (function(){
        var json = null;
        $.ajax({
            'async': false,
            'global': false,
            'url': 'subs/ajax_cbp_req.php?call=cbp&subq=get_CommBuildTypes&optype=json',
            'dataType': "json",
            'success': function (data) {
                json = data;
            }
        });

        return json;
    })();
    
    var srsJSON =  (function(){
        var json = null;
        $.ajax({
            'async': false,
            'global': false,
            'url': 'subs/ajax_cbp_req.php?call=cbp&subq=get_SalesReps&optype=json&oid=' + active_office,
            'dataType': "json",
            'success': function (data) {
                json = data;
            }
        });

        return json;
    })();
    
    $('.frmcopySingle').click(function(){
        
        if ($('#toids').val()!=0)
        {
            var ids=$('#toids').val();
            
            $('<input type="hidden" name="stoid" value="' + ids + '">').prependTo($(this));
            $(this).closest('form.frmcopySingle').submit();
        }
        else
        {
            alert('Select a target Office to copy this Profile');
        }
    });
    
    $('#CommBuilderProfileTabs').tabs({
        cache:false,
        selected:cbp_view_tab_id,
        spinner:'Retrieving data...',
        show:function(event,ui){
            var show_tab_id = ui.index;
            $.cookie("cbp_view_menu_tab", show_tab_id);
            
            $('#CommBuildWorkSheet').remove();
            
            switch(show_tab_id)
            {
                case 0:
                    $('#panel_CommProfilesNewBuild').html(cbws);
                    set_CommProfilesWorkSpace();
				break;
                
                case 1:
                    $('#panel_CommProfilesRenovation').html(cbws);
                    set_CommProfilesWorkSpace();
				break;
            }
        }
    });
    
    function displayULLIType(json,ct)
    {
        var out='';
        
        out+='<ul>';
        
        if (ct==='active_salesr')
        {
            out+='<li class="' + ct + '" id="0"><span class="srcats">All Sales Reps</span></li>';
        }

        $.each(json, function (k,v){
            out+='<li class="' + ct + '" id="' + k + '">';
            
            $.each(v, function (k1,v1){
                out+='<span class="' + k1 + '">' + v1 + '</span><span class="cpstatus"></span>';
            });
            
            out+='</li>';
        });
        
        out+='</ul>';
        return out;
    }
    
    function displaySELECTType(json)
    {
        var out='';
        
        out+='<select id="active_salesr"><option value="0" SELECTED>All</option>';
        
        $.each(json, function (k,v){
            out+='<option value="' + k + '">';
            
            $.each(v, function (k1,v1){
                out+='<span class="' + k1 + '">' + v1 + '</span>';
            });
            
            out+='</option>';
        });
        
        out+='</select>';
        return out;
    }
    
    function show_cpstatus()
    {
        var out='<img src="images/action_check.gif">';
        return out;
    }
    
    function show_cpitem2()
    {
        var out='';
        
        out+='<div id="displayCommOption">';
        out+='<ul>';
        out+='<li><span class="CB_Item">Begin Date <input type="text" id="ncp_d1" size="7"></span></li>';
        out+='<li><span class="CB_Item">End Date <input type="text" id="ncp_d2" size="7"></span></li>';
        out+='<li><span class="CB_Item">Bonus Type <select id="ncp_ctype"><option value="0">Select...</option><option value="1">Fixed</option><option value="2">Percent</option></select></span></li>';
        out+='<li><span class="CB_Item">Bonus % Rate <input type="text" id="ncp_rwdrate" value="0" size="7"></span></li>';
        out+='<li><span class="CB_Item">Bonus Fixed Amount <input type="text" id="ncp_rwdamt" value="0.00" size="7"></span></li>';
        out+='<li><span class="CB_Item">Source Type <select id="ncp_trgsrcval"><option value="0">None</option><option value="1">Retail Contract Amount</option><option value="2">Over/Under Commission</option><option value="3">Adjusted Contract Amount</option><option value="7">Total Commission</option></select></span></li>';
        out+='<li><span class="CB_Item">Trigger Source <select id="ncp_trgsrc"><option value="0">None</option><option value="1">Base Commission</option><option value="2">Over/Under Commission</option><option value="7">Total Commission</option><option value="4">Retail Contract Amount</option><option value="6">Bullet Weight</option></select></span></li>';
        out+='<li><span class="CB_Item">Trigger Weight <input type="text" id="ncp_trgwght" value="0" size="7"></span></li>';
        out+='<li><span class="CB_Item">Trigger Amount <input type="text" id="ncp_trgamt" value="0" size="7"></span></li>';
        out+='<li><span class="CB_Item">Create Profile Group <input type="checkbox" id="ncp_creategroup"></span></li>';
        out+='</ul>';
        out+='</div>';
        
        return out;
    }
    
    function show_cpitem(data)
    {
        var cmid=data[0];
        var d1=data[1];
        var d2=data[2];
        var ctype=data[3];
        var rwdrate=data[4];
        var rwdamt=data[5];
        var trgwght=data[6];
        var trgsrc=data[7];
        var trgsrcval=data[8];
        var linkid=data[9];
        
        //alert(cmid);

        //$('#CommBuildEditSpace').append('<input type="text" id="ncp_cmid" value="0">');
        $('#CommBuildEditSpace').append('<input type="hidden" id="ncp_cmid" value="0">');
        $('#CommBuildEditSpace').append('<table>');
        $('#CommBuildEditSpace').append('<tr id="line_ncp_ctype"><td class="CB_label">Bonus Type</td><td class="CB_Item"><img class="display_help" id="ncp_ctype_help" src="images/help.png"> <select id="ncp_ctype"><option value="0">Select...</option><option value="1">Fixed</option><option value="2">Percent</option></select></td></tr>');
        $('#CommBuildEditSpace').append('<tr id="line_ncp_rwdamt"><td class="CB_label">Bonus Fixed Amount</td><td class="CB_Item"><img class="display_help" id="ncp_rahelp" src="images/help.png"> <input type="text" id="ncp_rwdamt" size="7"></td></tr>');
        $('#CommBuildEditSpace').append('<tr id="line_ncp_rwdrate"><td class="CB_label">Bonus % Rate</td><td class="CB_Item"><img class="display_help" id="ncp_rrhelp" src="images/help.png"> <input type="text" id="ncp_rwdrate" size="7"></td></tr>');
        $('#CommBuildEditSpace').append('<tr id="line_ncp_trgsrcval"><td class="CB_label">Source Type</td><td class="CB_Item"><img class="display_help" id="ncp_svhelp" src="images/help.png"> <select id="ncp_trgsrcval"><option value="0">None</option><option value="1">Retail Contract Amount</option><option value="2">Over/Under Commission</option><option value="3">Adjusted Contract Amount</option><option value="7">Total Commission</option></select></td></tr>');
        $('#CommBuildEditSpace').append('<tr id="line_ncp_trgsrc"><td class="CB_label">Trigger Source</td><td class="CB_Item"><img class="display_help" id="ncp_tshelp" src="images/help.png"> <select id="ncp_trgsrc"><option value="0">None</option><option value="1">Base Commission</option><option value="2">Over/Under Commission</option><option value="7">Total Commission</option><option value="4">Retail Contract Amount</option><option value="6">Bullet Weight</option></select></td></tr>');
        $('#CommBuildEditSpace').append('<tr id="line_ncp_trgwght"><td class="CB_label">Trigger Weight</td><td class="CB_Item"> <img class="display_help" id="ncp_twhelp" src="images/help.png"> <input type="text" id="ncp_trgwght" size="7"></td></tr>');
        //$('#CommBuildEditSpace').append('<tr class="line_ncp_creategroup"><td class="CB_label">Create Profile Group</td><td class="CB_Item"><input type="checkbox" id="ncp_creategroup"></td></tr>');
        $('#CommBuildEditSpace').append('</table>');
        
        $('#ncp_cmid').val(cmid);
        $('#ncp_ctype').val(ctype);
        
        if (ctype==1)
        {
            $('#line_ncp_rwdrate').hide();
            $('#ncp_rwdamt').val(rwdamt);
        }
        
        if (ctype==2)
        {
            $('#line_ncp_rwdamt').hide();
            $('#ncp_rwdrate').val(parseFloat(rwdrate));
        }
        
        $('#ncp_trgsrcval').val(trgsrcval);
        $('#ncp_trgsrc').val(trgsrc);
        
        return;
    }
    
    function show_cpitem_blank()
    {
        $('#CommBuildEditSpace').append('<input type="hidden" id="ncp_cmid" value="0">');
        $('#CommBuildEditSpace').append('<table>');
        $('#CommBuildEditSpace').append('<tr id="line_ncp_ctype"><td class="CB_label">Bonus Type</td><td class="CB_Item"><img class="display_help" id="ncp_srhelp" src="images/help.png"><select id="ncp_ctype"><option value="0">Select...</option><option value="1">Fixed</option><option value="2">Percent</option></select></td></tr>');
        $('#CommBuildEditSpace').append('<tr id="line_ncp_rwdamt"><td class="CB_label">Bonus Fixed Amount</td><td class="CB_Item"><input type="text" id="ncp_rwdamt" size="7"></td></tr>');
        $('#CommBuildEditSpace').append('<tr id="line_ncp_rwdrate"><td class="CB_label">Bonus % Rate</td><td class="CB_Item"><input type="text" id="ncp_rwdrate" size="7"></td></tr>');
        $('#CommBuildEditSpace').append('<tr id="line_ncp_trgsrcval"><td class="CB_label">Source Type</td><td class="CB_Item"><select id="ncp_trgsrcval"><option value="0">None</option><option value="1">Retail Contract Amount</option><option value="2">Over/Under Commission</option><option value="3">Adjusted Contract Amount</option><option value="7">Total Commission</option></select></td></tr>');
        $('#CommBuildEditSpace').append('<tr id="line_ncp_trgsrc"><td class="CB_label">Trigger Source</td><td class="CB_Item"><select id="ncp_trgsrc"><option value="0">None</option><option value="1">Base Commission</option><option value="2">Over/Under Commission</option><option value="7">Total Commission</option><option value="4">Retail Contract Amount</option><option value="6">Bullet Weight</option></select></td></tr>');
        $('#CommBuildEditSpace').append('<tr id="line_ncp_trgwght"><td class="CB_label">Trigger Weight</td><td class="CB_Item"><input type="text" id="ncp_trgwght" size="7"></td></tr>');
        $('#CommBuildEditSpace').append('</table>');
        
        return;
    }
    
    function get_CommProfilesJSON(oid,sid,ctgry,renov)
    {
        //alert(oid + ':' + sid + ':' + cbpt);
        $.ajax({
            cache:false,
            type : 'POST',
            url : cbpscript,
            data: {
                'call' : 'cbp',
                'subq' : 'get_CommProfilesJSON',
                'oid' : oid,
                'sid' : sid,
                'reno' : renov,
                'catg' : ctgry,
                'optype': 'json'
            },
            dataType: 'json',
            success: function (data) {
                
                //$('#CommBuildEditSpace').append(show_cpitem_blank());
                
                if (typeof data.profiles=='object')
                {
                    show_cpitem(data.profiles);
                }
                else
                {
                    var pdata='<table><tr><td>Commission Profile does not exist. Create one by filling out the form and Submitting</td></tr></table>';
                    $('#CommBuildEditSpace').append(pdata);
                    $('#CommBuildEditSpace').append(show_cpitem_blank());
                }
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
                alert('Error: ' + textStatus);
                //json = textStatus;
            }
        });
    }
    
    function get_CommProfilesHTML(oid,sid,ctgry,reno)
    {
        //alert(oid + ':' + sid + ':' + cbpt);
        $.ajax({
            cache:false,
            type : 'POST',
            url : cbpscript,
            data: {
                'call' : 'cbp',
                'subq' : 'get_CommProfiles',
                'oid' : oid,
                'sid' : sid,
                'ren' : cbpt,
                'catg' : ctgry,
                'optype': 'html'
            },
            dataType: 'html',
            success: function (data) {
                //alert(data);
                var pdata=show_cpitem(data);
                $('#CommBuildEditSpace').append(pdata);
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
                alert('Error: ' + textStatus);
            }
        });
    }
    
    function CommBuildWorkSheet()
    {
        var out='';
        
        out+='<table width="910px" id="CommBuildWorkSheet">';
        out+='   <tr>';
        out+='      <td width="150px" align="left" valign="top" id="CommBuildSRList"></td>';
        out+='      <td width="150px" align="left" valign="top" id="CommBuildTypeList"></td>';
        out+='      <td align="left" valign="top" id="CommBuildEditSpace">Select a Sales Rep and Commission Type to show active Commission Profiles</td>';
        out+='      <td width="150px" align="left" valign="top" id="CommBuildInfoField"></td>';
        out+='   </tr>';
        out+='</table>';
        
        return out;
    }
    
    function set_CommProfilesWorkSpace()
    {
        var aprf=addProfile();
        var dcts=displayULLIType(cbtJSON,'cbcat_list');
        var dsrl=displayULLIType(srsJSON,'active_salesr');
        
        $('#CommBuildSRList').html('<b>Sales Reps</b> <img id="ncp_srhelp" src="images/help.png">');
        $('#CommBuildSRList').append(dsrl);
        $('#CommBuildTypeList').html('<b>Commission Types</b> <img id="ncp_cphelp" src="images/help.png">');
        $('#CommBuildTypeList').append(dcts);        
        return;
    }
    
    function addProfile()
    {
        var out='';
        
        out+='<a href="#">Add <img src="../images/add.png" class="addNewCBProfile" title="Add Profile"></a>';
        
        return out;
    }

    function show_CommProfiles(oid,sid,ctgry,cbpt)
    {
        //get_CommProfilesHTML(oid,sid,ctgry,cbpt);
        get_CommProfilesJSON(oid,sid,ctgry,cbpt);
        return;
    }

    $('.cbcat_list').live('click',function(){
        $('.cbcat_list').removeClass("cbtype_select");
        $(this).addClass("cbtype_select");
        
        if ($('.active_salesr_select').length == 0)
        {
            $('#CommBuildSRList ul li:first-child').addClass('active_salesr_select');
        }
        
        $('#CommBuildEditSpace').empty();
        
        var catid=$(this).attr('id');
        var srsid=$('#CommBuildSRList ul li.active_salesr_select').attr('id');
        var cbtid=parseInt($.cookie("cbp_view_menu_tab"));
        
        $.cookie("active_salesr", srsid);
        
        $('#CommBuildEditSpace').html('<b><i>' + $(this).html() + '</i> Commission Profile(s) for ' + $('#CommBuildSRList ul li.active_salesr_select').html() + '</b>');
        show_CommProfiles(active_office,srsid,catid,cbtid);
        return;
    });
    
    $('.active_salesr').live('click',function(){
        $('.active_salesr').removeClass("active_salesr_select");
        $(this).addClass("active_salesr_select");
        
        if ($('.cbtype_select').length == 0)
        {
            $('#CommBuildTypeList ul li:first-child').addClass('cbtype_select');
        }
        
        $('#CommBuildEditSpace').empty();
        
        var catid=$('#CommBuildTypeList ul li.cbtype_select').attr('id');
        var srsid=$(this).attr('id');
        var cbtid=parseInt($.cookie("cbp_view_menu_tab"));
        
        $.cookie("active_salesr", srsid);
        
        $('#CommBuildEditSpace').html('<b><i>' + $('#CommBuildTypeList ul li.cbtype_select').html() + '</i> Commission Profile(s) for ' + $(this).html() + '</b>');
        
        show_CommProfiles(active_office,srsid,catid,cbtid);
        return;
    });
    
    $('.cbcat_list').live('mouseover mouseout',function(ev){
        if (ev.type=='mouseover')
        {
            $(this).css("cursor", "pointer");
            $(this).addClass("cbtype_hover");
        }
        
        if (ev.type=='mouseout')
        {
            $(this).removeClass("cbtype_hover");
        }
    });
    
    $('.active_salesr').live('mouseover mouseout',function(ev){
        if (ev.type=='mouseover')
        {
            $(this).css("cursor", "pointer");
            $(this).addClass("cbtype_hover");
        }
        
        if (ev.type=='mouseout')
        {
            $(this).removeClass("cbtype_hover");
        }
    });
    
    $('#active_salesr').live('change',function(){
        var catid=$('.cbtype_select').attr('id');
        var srsid=$('#active_salesr').val();
        var cbtid=parseInt($.cookie("cbp_view_menu_tab"));
        
        $.cookie("active_salesr", srsid);        
        $('#CommBuildEditSpace').empty();
        $('#CommBuildEditSpace').html('<b><i>' + $('.cbtype_select').html() + '</i> Commission Profiles for ' + $('#active_salesr option:selected').html() + '</b>');
        show_CommProfiles(active_office,srsid,catid,cbtid);
    });
    
    function displayHelp(event,landingEl)
    {
        var help_text = '';
        $(landingEl).append('<div id="js_helpnode"></div>');
        
        switch ($(event.target).attr('id'))
        {
            case 'ncp_salesrep_help':
                var help_text = 'Selecting All assigns the Commission Profile to All Sales Reps. Selecting a specific Sales rep assigns the Commission Profile to that Sales Rep only.';
            break;
        
            case 'ncp_ctgry_help':
                var help_text = 'Determines Commission Type';
            break;
        
            case 'ncp_d1_help':
                var help_text = 'Bonus Start Date';
            break;
        
            case 'ncp_d2_help':
                var help_text = 'Bonus End Date';
            break;
        
            case 'ncp_ctype_help':
                var help_text = 'Bonus Type';
            break;
        
            case 'ncp_rwdrate_help':
                var help_text = 'Bonus Percentage';
            break;
        
            case 'ncp_rwdamt_help':
                var help_text = 'Bonus Amount';
            break;
        
            case 'ncp_trgsrc_help':
                var help_text = 'Trigger Source';
            break;
        
            case 'ncp_trgsrcval_help':
                var help_text = 'Trigger Source Value';
            break;
        
            case 'ncp_trgwght_help':
                var help_text = 'Trigger Weight';
            break;
        
            case 'ncp_trgamt_help':
                var help_text = 'Trigger Amount';
            break;
        
            case 'ncp_trenov_help':
                var help_text = 'Renovation Only';
            break;
        
            case 'ncp_creategroup_help':
                var help_text = 'Commission Group Create';
            break;
        }
        
        if (event.type=='mouseover')
        {
            //$(event.target).parent().addClass("ui-state-highlight");
            //$('#NewCommOptionInfo').addClass("ui-state-highlight");
            $('#js_helpnode').append(help_text);
        }
        
        if (event.type=='mouseout')
        {
            //$(event.target).parent().removeClass("ui-state-highlight");
            //$('#NewCommOptionInfo').removeClass("ui-state-highlight");
            $('#js_helpnode').text('');
        }
    }
    
    function getSalesReps(oid)
    {
        var out;
        
        $.ajax({
            cache:false,
            type : 'POST',
            url : usrscript,
            dataType : 'html',
            data: {
                call : 'user',
                subq : 'get_SalesReps_JSON',
                oid : oid,
                optype: 'json'
            },
            success : function(data){
                out = data;
                //alert(data);
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
                alert('Error: ' + textStatus);
            }
        });
        
        return out;
    }
    
    function BuildNewCommProfileDialog()
    {
        var dialogTriggerEl='<div id="NewCommProfile"></div>';
        $('#CommProfileWorkSpace').append(dialogTriggerEl);
        
        $("#NewCommProfile").dialog({
            modal: true,
            height: 400,
            width: 600,
            position: 'center',
            title: 'Add Commission Builder Profile(s)',
            closeText: 'Close',
            buttons: {
                "Update": function()
                {
                    alert(getSalesReps(window.ActiveOffice));
                },
                "Cancel": function(){
                    $(this).dialog('close');
                }
            },
            close: function(){
                $('#NewCommProfile').remove();
            }
        });
        
        $('#NewCommProfile').append('<div id="NewCommOption"><ul>');
        $('#NewCommProfile').append('<li><span class="CB_Item">Sales Rep <select id="ncp_salesrep"><option value="0">All</option></select></span></li>');
        $('#NewCommProfile').append('<li><span class="CB_Item">Commission Type <select id="ncp_ctgry"><option value="0">Select...</option><option value="1">Sales Rep Base</option><option value="2">Sales Rep Over/Under</option><option value="6">Sales Rep Bullet</option><option value="4">Sales Manager</option><option value="7">General Manager</option></select></span></li>');
        $('#NewCommProfile').append('<li><span class="CB_Item">Begin Date <input type="text" id="ncp_d1" size="7"></span></li>');
        $('#NewCommProfile').append('<li><span class="CB_Item">End Date <input type="text" id="ncp_d2" size="7"></span></li>');
        $('#NewCommProfile').append('<li><span class="CB_Item">Bonus Type <select id="ncp_ctype"><option value="0">Select...</option><option value="1">Fixed</option><option value="2">Percent</option></select></span></li>');
        $('#NewCommProfile').append('<li><span class="CB_Item">Bonus % Rate <input type="text" id="ncp_rwdrate" value="0" size="7"></span></li>');
        $('#NewCommProfile').append('<li><span class="CB_Item">Bonus Fixed Amount <input type="text" id="ncp_rwdamt" value="0.00" size="7"></span></li>');
        $('#NewCommProfile').append('<li><span class="CB_Item">Source Type <select id="ncp_trgsrcval"><option value="0">None</option><option value="1">Retail Contract Amount</option><option value="2">Over/Under Commission</option><option value="3">Adjusted Contract Amount</option><option value="7">Total Commission</option></select></span></li>');
        $('#NewCommProfile').append('<li><span class="CB_Item">Trigger Source <select id="ncp_trgsrc"><option value="0">None</option><option value="1">Base Commission</option><option value="2">Over/Under Commission</option><option value="7">Total Commission</option><option value="4">Retail Contract Amount</option><option value="6">Bullet Weight</option></select></span></li>');
        $('#NewCommProfile').append('<li><span class="CB_Item">Trigger Weight <input type="text" id="ncp_trgwght" value="0" size="7"></span></li>');
        $('#NewCommProfile').append('<li><span class="CB_Item">Trigger Amount <input type="text" id="ncp_trgamt" value="0" size="7"></span></li>');
        $('#NewCommProfile').append('<li><span class="CB_Item">Renovation Only <input type="checkbox" id="ncp_trenov"></span></li>');
        $('#NewCommProfile').append('<li><span class="CB_Item">Create Profile Group <input type="checkbox" id="ncp_creategroup"></span></li>');
        $('#NewCommProfile').append('<li><span id="CB_Profile_Group">XXX</span></li>');
        $('#NewCommProfile').append('</ul></div></td>');
        $('#NewCommProfile').append('<div id="NewCommOptionInfo"></div>');
        
        //$('li > span#CB_Profile_Group').hide();
        
        $('#ncp_d1').datepicker();
        $('#ncp_d2').datepicker();
        
        //$('#NewCommOption > ul').css('float','left');
        $('#NewCommOption').css('width','300px');
        $('#NewCommOptionInfo').css('width','300px');
        
        //$('#NewCommOption').parent().addClass("ui-state-hover");
    }
    
    $('#addNewCommProfile').click(function(){
        BuildNewCommProfileDialog();
    });
    
    //CSS inits
    //$('#NewCommOption').css('border-width','1px');
    //$('#NewCommOption').css('background-color','#d3d3d3');
    
    
    // Help Triggers
    $('.display_help').live('mouseover mouseout',function(event){
        //alert('HELP: ' + $(this).attr('id'));
        displayHelp(event,$(this).attr('id'));
    });
    
    /*
    $('#ncp_salesrep').live('mouseover mouseout',function(event){
        displayHelp(event,'#NewCommOptionInfo');
    });
    
    $('#ncp_ctgry').live('mouseover mouseout',function(event){
        displayHelp(event,'#NewCommOptionInfo');
    });
    
    $('#ncp_d1').live('mouseover mouseout',function(event){
        displayHelp(event,'#NewCommOptionInfo');
    });
    
    $('#ncp_d2').live('mouseover mouseout',function(event){
        displayHelp(event,'#NewCommOptionInfo');
    });
    
    $('#ncp_ctype').live('mouseover mouseout',function(event){
        displayHelp(event,'#NewCommOptionInfo');
    });
    
    $('#ncp_trgsrcval').live('mouseover mouseout',function(event){
        displayHelp(event,'#NewCommOptionInfo');
    });
    
    $('#ncp_trgsrc').live('mouseover mouseout',function(event){
        displayHelp(event,'#NewCommOptionInfo');
    });
    
    $('#ncp_trgwght').live('mouseover mouseout',function(event){
        displayHelp(event,'#NewCommOptionInfo');
    });
    
    $('#ncp_trgamt').live('mouseover mouseout',function(event){
        displayHelp(event,'#NewCommOptionInfo');
    });
    
    $('#ncp_rwdrate').live('mouseover mouseout',function(event){
        displayHelp(event,'#NewCommOptionInfo');
    });
    
    $('#ncp_rwdamt').live('mouseover mouseout',function(event){
        displayHelp(event,'#NewCommOptionInfo');
    });
    
    $('#ncp_trenov').live('mouseover mouseout',function(event){
        displayHelp(event,'#NewCommOptionInfo');
    });
    
    $('#ncp_creategroup').live('mouseover mouseout',function(event){
        displayHelp(event,'#NewCommOptionInfo');
    });
    */
});