$(document).ready(function()
{    
    var mposX;
    var mposY;
    
    $(document).mousemove(function (e) {
        mposX = e.pageX;
        mposY = e.pageY;
    });
    
    var iebrwser    = $.browser.msie;
    
    var cbp_view_tab_id = parseInt($.cookie("cbp_view_menu_tab")) || 0;
    var show_warning = 0;
    var active_office= parseInt($('#active_office').val()) || 0;
    var active_salesr= parseInt($('#active_salesr').val()) || 0;
    var usrscript   = 'subs/ajax_user_req.php';
    var cbpscript   = 'subs/ajax_cbp_req.php';
    var spinnerIMG  = '<span id="spinnerIMG"><p><img src="../images/mozilla_blu.gif"> Retrieving...</p></span>';
	var updateTEXT  = '<em>Updating....</em>';
    var updateHTML  = '<img src="../images/mozilla_blu.gif"> Updating...';
    var processIMG	= '<img src="../images/mozilla_blu.gif"> Processing...';
    var triggercatids= new Array(6,9);
    var weightcatids= new Array(6,9);
    var allowSave   = false;
    var block_tier  = false;
    var active_cmid = 0;
    var val_err_txt = '';
    var cbws        = CommBuildWorkSheet();
    
    CommBuilderTab('body');
    
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
            'url': 'subs/ajax_cbp_req.php?call=cbp&subq=get_SalesRepsJSON&optype=json&oid=' + active_office,
            'dataType': "json",
            'success': function (data) {
                json = data;
            }
        });

        return json;
    })();
    
    function apsJSON(reno){
        
        //alert(active_salesr);
        var json = null;
        $.ajax({
            'async': false,
            'global': false,
            'cache': false,
            'url': 'subs/ajax_cbp_req.php?call=cbp&subq=get_AllCats&optype=json&oid=' + active_office + '&sid=' + active_salesr + '&renov=' + reno,
            'dataType': "json",
            'success': function (data) {
                json = data;
            }
        });

        return json;
    }
    
    /*
    var offJSON =  (function(){
        var json = null;
        $.ajax({
            'async': false,
            'global': false,
            'url': 'subs/ajax_cbp_req.php?call=cbp&subq=get_OfficeInfo&optype=json&oid=' + active_office,
            'dataType': "json",
            'success': function (data) {
                json = data;
            }
        });

        return json;
    })();
    */
    
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
    
    function CommBuilderTab(el)
    {
        //$(el).append('<table id="CSWrkspc" align="center" width="940px"><tr><td></td></tr></table>');
        //$(el).append('<div id="hlpdlgdisp"></div>');
        $(el).append('<div id="CommProfileWorkSpace"></div>');
        
        var out='';
		out+='<div id="CommBuilderProfileTabs">';
		out+='  <ul>';
		out+='      <li><a href="#tab0"><span>New Construction</span></a></li>';
		out+='      <li><a href="#tab1"><span>Renovations</span></a></li>';
		//out+='      <li><a href="#tab2"><em>Commission Settings</em></a></li>';
		out+='  </ul>';
		out+='  <div id="tab0">';
		out+='      <div id="panel_CommProfilesNewBuild"></div>';
		out+='  </div>';
		out+='	<div id="tab1">';
		out+='		<div id="panel_CommProfilesRenovation"></div>';
		out+='	</div>';
		//out+='	<div id="tab2">';
		//out+='		<div id="panel_CommProfilesSettings"></div>';
		//out+='	</div>';
		out+='</div>';
        
        $('#CommProfileWorkSpace').append(out);
    }
    
    $('#CommBuilderProfileTabs').tabs({
        cache:false,
        selected:cbp_view_tab_id,
        show:function(event,ui){
            var show_tab_id = ui.index;
            cbp_view_tab_id=show_tab_id;
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
                
                case 2:
                    $('#panel_CommProfilesSettings').html();
                    show_CommProfileSettings();
				break;
            }
        }
    });
    
    function show_BasicReqsCheck(t)
    {
        //var exps = 0;
        var profs=apsJSON(t);
        
        $('#CommBuildTypeList ul li').css('color','gray');
        
        //alert(profs);
        $.each(profs, function(k,v){
            
            //alert(v.catid +':'+v.active);
            
            if (v.active===1)
            {
                $('#CommBuildTypeList ul li#' + v.catid).css('color','green');
            }
            
            if (v.active===0)
            {
                $('#CommBuildTypeList ul li#' + v.catid).css('color','red');
            }
            
            if (v.catid===1)
            {
                if (active_salesr==0 && v.active===0)
                {
                    alert('This office has no Base Commission configured.');
                    //exps++;
                }
            }
        });
    }
    
    function displayRepList(json,ct)
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
    
    function displayCPList(json,ct)
    {
        var out='';
        
        out+='<ul>';

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
    
    function CommBuildWorkSheet()
    {
        var out='';
        
        out+='<table width="910px" id="CommBuildWorkSheet">';
        out+='   <tr>';
        out+='      <td width="150px" align="left" valign="top" id="CommBuildSRList"><br></td>';
        out+='      <td width="150px" align="left" valign="top" id="CommBuildTypeList"><br></td>';
        out+='      <td align="left" valign="top" id="CommBuildEditSpace"><br></td>';
        //out+='      <td width="275px" align="left" valign="top" id="CommBuildInfoField"><b>Help</b><br>Select a Commission Recipient or Commission Category</td>';
        out+='   </tr>';
        out+='</table>';
        
        return out;
    }
    
    function set_CommProfilesWorkSpace()
    {
        var dcts=displayCPList(cbtJSON,'cbcat_list');
        var dsrl=displayRepList(srsJSON,'active_salesr');
        
        $('#CommBuildSRList').html('<b>Recipient</b> <img src="../images/info.gif" class="hlpdlg" id="srhelptext">');
        $('#CommBuildSRList').append(dsrl);
        $('#CommBuildTypeList').html('<b>Category</b> <img src="../images/info.gif" class="hlpdlg" id="cchelptext">');
        $('#CommBuildTypeList').append(dcts);
        $('#CommBuildEditSpace').html('<b><span class="display_help" id="cwhelptext">Workspace</span></b>');
        return;
    }
    
    function show_CommProfilesSettings()
    {
        if ($('#generalSettings').length == 0)
        {
            $('#panel_CommProfilesSettings').append('<fieldset id="generalSettings"><p><b>General</b></p></fieldset>');
            $('#generalSettings').append('Allow Minimum Override <input type="checkbox" id="allowMinimumOverride" value="1">');
        }
    }
    
    function show_cpstatus()
    {
        var out='<img src="images/action_check.gif">';
        return out;
    }
    
    function show_CommCategories()
    {
        alert('Parsing Categories');
        return;
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
        out+='<li><span class="CB_Item">Source Type <select id="ncp_trgsrcval"><option value="0">None</option><option value="1">Retail Contract Amount</option><option value="2">Over/Under Commission</option><option value="3">Adjusted Contract Amount</option><option value="4">Pricebook Total (with Exclusions)</option><option value="7">Total Commission</option></select></span></li>';
        out+='<li><span class="CB_Item">Trigger Source <select id="ncp_trgsrc"><option value="0">None</option><option value="1">Base Commission</option><option value="2">Over/Under Commission</option><option value="7">Total Commission</option><option value="4">Retail Contract Amount</option><option value="6">Bullet Weight</option></select></span></li>';
        out+='<li><span class="CB_Item">Trigger Weight <input type="text" id="ncp_trgwght" value="0" size="7"></span></li>';
        out+='<li><span class="CB_Item">Trigger Amount <input type="text" id="ncp_trgamt" value="0" size="7"></span></li>';
        out+='<li><span class="CB_Item">Create Profile Group <input type="checkbox" id="ncp_creategroup"></span></li>';
        out+='</ul>';
        out+='</div>';
        
        return out;
    }
    
    function show_cpitem(data,ctgry)
    {
        $('#CommBuildInfoField').html('<b>Help</b>');
        
        if (ctgry==6 || ctgry==9)
        {
            show_cpitem_multi_blank(data,ctgry);
        }
        else
        {
            show_cpitem_single_blank(data,ctgry,'#CommBuildEditSpace');
        }
        
        return;
    }
    
    $('.add_cp_tier').live('click',function(){    
        if (!block_tier)
        {
            block_tier=true;
            $('.add_cp_tier').hide();
            $('.del_existing_cp_tier').hide();
            
            var dcs ='srsc_NEW';
            var pdcs='#' + dcs;
            var new_tier=parseInt($('#CommAccordion').accordion( "option", "active")) + 1;
            var parent_cmid=parseInt($('#ncp_cmid_display').html());
            var parent_ctype=parseInt($('#ncp_ctype').val());
            
            $('#CommAccordion').append('<h3><a href="#">New Tier</a></h3>');
            $('#CommAccordion').append('<div id="'+ dcs +'"></div>');
            
            $(pdcs).append('<table>');
            $(pdcs).append('<tr id="line_ncp_cmid_new"><td align="right">Parent cmID</td><td align="left"><span id="new_tier_pcmid">'+ parent_cmid +'</span></td></tr>');
            
            if (parent_ctype==1)
            {
                $(pdcs).append('<tr id="line_ncp_rwdamt_new"><td align="right">Fixed Amount</td><td align="left"><input type="text" id="new_tier_rwdamt" size="5"><input type="hidden" id="new_tier_rwdrate" value="0"></td></tr>');
            }
            
            if (parent_ctype==2)
            {
                $(pdcs).append('<tr id="line_ncp_rwdrate_new"><td align="right">Percentage Rate</td><td align="left"><input type="text" id="new_tier_rwdrate" size="5"><input type="hidden" id="new_tier_rwdamt" value="0"></td></tr>');
            }
            
            $(pdcs).append('<tr id="line_ncp_trgwght_new"><td align="right">Weight</td><td align="left"><input class="display_help" type="text" id="new_tier_trgwght" size="5"></td></tr>');
            $(pdcs).append('<tr id="line_save_commprofile_new"><td><div id="show_EditTierNEW"></div></td><td align="right"><a href="#" id="new_tier_save" id="0">Save <img src="images/save.gif" title="Save Profile"></a></td></tr>');
            $(pdcs).append('</table>');
            
            $('#show_EditTierNEW').append('<a href="#" class="del_new_cp_tier"><img src="../images/delete.png"> Del Tier</a>');
            
            $('#CommAccordion').accordion('destroy').accordion();
            $('#CommAccordion').accordion('option','active',new_tier);
        }
    });
    
    $('.del_new_cp_tier').live('click',function(){
        
        $('div#srsc_NEW').remove();
        $('h3.ui-state-active').remove();
        
        block_tier=false;
        $('.add_cp_tier').show();
        $('.del_existing_cp_tier').show();
        $('#CommAccordion').accordion('destroy').accordion();
        $('#CommAccordion').accordion('option','active',($('#CommAccordion h3').size() -1));
    });
    
    function rateexists(oid,cp_data,nr)
    {
        var out=false;
        
        if (cp_data.length==1)
        {
            if (cp_data.rwdrate==nr)
            {
                val_err_txt=': Rate Exists';
                out=true;
            }
        }
        else
        {
            $.each(cp_data, function(k1,v1){
                if (v1.rwdrate==nr)
                {
                    val_err_txt=': Rate Exists';
                    out=true;
                }
            });
        }

        return out;
    }
    
    function weightexists(oid,cp_data,nt)
    {
        var out=false;
        
        $.each(cp_data, function(k1,v1){
            if (v1.trgwght==nt)
            {
                val_err_txt=': Weight Exists';
                out=true;
            }
        });

        return out;
    }
    
    function show_cpitem_multi_blank(data,ctgry)
    {
        var dlen=$(data).length;
        //alert(data);
        if (dlen > 0)
        {
            var ca  ='CommAccordion';
            var pca ='#'+ ca;

            block_tier=false;
            
            $('#CommBuildEditSpace').append('<div id="'+ ca +'"></div>');
            
            if (dlen == 1)
            {
                var disp_dcs=1;
                var dcs='scsrc_' + data.cmid;
                var pdcs='#' + dcs;
                
                $(pca).append('<h3><a href="#">Tier ' + disp_dcs + '</a></h3>');
                $(pca).append('<div id="'+ dcs +'"></div>');
                
                show_cpitem_single_blank(data,ctgry,pdcs);
                
                $('#savetierenable').append('<div id="show_EditTier'+ data.cmid +'"></div>');
                
                if (dlen==disp_dcs && !block_tier)
                {
                    $('#show_EditTier'+ data.cmid).append('<a href="#" class="add_cp_tier"><img src="../images/add.png"> Add Tier</a>');
                }
                
                if (dlen==disp_dcs && !block_tier)
                {
                    $('#show_EditTier'+ data.cmid).append('<a href="#" id="del_comm_profile"><img src="../images/delete.png"> Delete</a>');
                }
                
                $('#CommAccordion').accordion();
            }
            else
            {
                $.each(data, function(key, value)
                {
                    var disp_dcs=(key + 1);
                    var dcs='scsrc_' + value.cmid;
                    var pdcs='#' + dcs;
                    
                    if (key == 0)
                    {
                        active_cmid=value.cmid;
                        $(pca).append('<h3><a href="#">Tier ' + disp_dcs + '</a></h3>');
                        $(pca).append('<div id="'+ dcs +'"></div>');
                        
                        show_cpitem_single_blank(value,ctgry,pdcs);
                    }
                    else
                    {
                        $(pca).append('<h3><a href="#">Tier ' + disp_dcs + '</a></h3>');
                        $(pca).append('<div id="'+ dcs +'"></div>');
                        
                        $(pdcs).append('<table>');
                        
                        if (dlen==disp_dcs)
                        {
                            $(pdcs).append('<tr id="line_ncp_cmid'+ value.cmid+'"><td align="right">cmID</td><td align="left" id="ncp_lcmid">'+ value.cmid +'</td></tr>');
                        }
                        
                        $(pdcs).append('<tr id="line_ncp_pcmid'+ value.cmid+'"><td align="right">Parent cmID</td><td align="left" id="ncp_pcmid">'+ active_cmid +'</td></tr>');
                        $(pdcs).append('<tr id="line_ncp_rwdamt'+ value.cmid+'"><td align="right">Fixed Amount</td><td align="left"><input type="text" id="ncp_rwdamt" value="'+ value.rwdamt +'" size="5"></td></tr>');
                        $(pdcs).append('<tr id="line_ncp_rwdrate'+ value.cmid+'"><td align="right">Percentage Rate</td><td align="left"><input type="text" id="ncp_rwdrate" value="'+ value.rwdrate +'" size="5"></td></tr>');
                        $(pdcs).append('<tr id="line_ncp_trgwght'+ value.cmid+'"><td align="right">Weight</td><td align="left"><input type="text" id="ncp_trgwght" value="'+ value['trgwght'] +'" size="5"></td></tr>');
                        $(pdcs).append('<tr id="line_save_commprofile'+ value.cmid+'"><td><div id="show_EditTier'+ value.cmid +'"></div></td><td align="right"><a href="#" class="save_new_CommProfile" id="'+ key +'">Save <img src="images/save.gif" title="Save Profile"></a></td></tr>');
                        $(pdcs).append('</table>');
                        
                        if (value.ctype==1)
                        {
                            $('#line_ncp_rwdrate'+ value.cmid).hide();
                        }
                        
                        if (value.ctype==2)
                        {
                            $('#line_ncp_rwdamt'+ value.cmid).hide();
                        }
                        
                        if (dlen==disp_dcs && !block_tier)
                        {
                            $('#show_EditTier'+ value.cmid).append('<a href="#" class="add_cp_tier"><img src="../images/add.png"> Add Tier</a>');
                        }
                        
                        if (dlen==disp_dcs && !block_tier)
                        {
                            $('#show_EditTier'+ value.cmid).append('<a href="#" class="del_existing_cp_tier"><img src="../images/delete.png"> Del Tier</a>');
                        }
                    }
                });
                
                $('#CommAccordion').accordion();
            }
        }
        else
        {
            alert('Data Length Zero');
        }
    }
    
    function show_cpitem_single_blank(data,catid,el)
    {
        var cmid=0;
        
        $(el).append('<input type="hidden" id="ncp_cmid" value="0">');
        $(el).append('<table>');
        $(el).append('<tr id="line_ncp_active"><td align="right">Active</td><td align="left"><span id="ncp_active_display"></span></td></tr>');
        $(el).append('<tr id="line_ncp_cmid"><td align="right">cmID</td><td align="left" id="ncp_cmid_display"></td></tr>');
        $(el).append('<tr id="line_ncp_catid"><td align="right">catID</td><td align="left" id="ncp_catid_display">'+ catid +'</td></tr>');
        $(el).append('<tr id="line_ncp_ctype"><td align="right">Commission Type</td><td align="left" id="ncp_ctype_display"><select id="ncp_ctype"><option value="0">Select...</option><option value="1">Fixed Amount</option><option value="2">Percent Rate</option></select> <img src="../images/info.gif" class="hlpdlg" id="ncp_ctype_help"></td></tr>');
        $(el).append('<tr id="line_ncp_rwdrate"><td align="right">Percentage Rate</td><td align="left"><input type="text" id="ncp_rwdrate" value="0" size="5"> <img src="../images/info.gif" class="hlpdlg" id="ncp_rwdrate_help"></td></tr>');
        $(el).append('<tr id="line_ncp_trgsrcval"><td align="right">Source Value</td><td align="left"><select id="ncp_trgsrcval"><option value="0">None</option><option value="1">Retail Contract Amount</option><option value="3">Adjusted Book Price</option><option value="4">Over/Under Variance</option><option value="7">Total Commission</option></select> <img src="../images/info.gif" class="hlpdlg" id="ncp_trgsrcval_help"></td></tr>');
		$(el).append('<tr id="line_ncp_rwdamt"><td align="right">Fixed Amount</td><td align="left"><input type="text" id="ncp_rwdamt" value="0.00" size="5"> <img src="../images/info.gif" class="hlpdlg" id="ncp_rwdamt_help"></td></tr>');
        $(el).append('<tr id="line_ncp_trgsrc"><td align="right">Trigger</td><td align="left"><select id="ncp_trgsrc"><option value="0">None</option><option value="1">Base Commission</option><option value="2">Over/Under Commission</option><option value="7">Total Commission</option><option value="4">Retail Contract Amount</option><option value="6">Bullet Weight</option></select> <img src="../images/info.gif" class="hlpdlg" id="ncp_trgsrc_help"></td></tr>');		
        $(el).append('<tr id="line_ncp_trgwght"><td align="right">Weight</td><td align="left"><input type="text" id="ncp_trgwght" size="5"> <img src="../images/info.gif" class="hlpdlg" id="ncp_rwdamt_help"></td></tr>');
        $(el).append('<tr id="line_save_commprofile"><td id="savetierenable"></td><td align="right"><span id="save_CommProfiles"></span</td></tr>');
        $(el).append('</table>');
        
        $('#line_ncp_catid').hide();
        
        if (data != null)
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
            var active_state=data[11];
            var ctype_display='';
            
            $('#line_ncp_cmid').show();
            $('#ncp_cmid').val(cmid);
            $('#ncp_cmid_display').text(cmid);
            $('#ncp_ctype').remove();
            
            if (active_state==1)
            {
                $('#ncp_active_display').text('Yes');
                $('#ncp_active_display').css('color','green');
                $('#ncp_active_display').append('<a href="#" class="JMStooltip" id="catid_deactivate" title="Deactivate this Profile"><img src="../images/bullet_wrench.png"></a>');
            }
            
            if (active_state==0)
            {
                $('#ncp_active_display').text('No');
                $('#ncp_active_display').css('color','red');
                $('#ncp_active_display').append('<a href="#" class="JMStooltip" id="catid_activate" title="Activate this Profile"><img src="../images/bullet_wrench.png"></a>');
            }
            
            if (ctype==1)
            {
                $('#ncp_ctype_display').text('Fixed');
                $('#ncp_ctype_display').append('<input type="hidden" id="ncp_ctype">');
                $('#ncp_ctype').val(ctype);
				$('#line_ncp_trgsrcval').show();
                $('#ncp_rwdamt').val(rwdamt);
                $('#line_ncp_rwdrate').hide();
                //$('#line_ncp_trgsrcval').hide();
                $('#save_CommProfiles').append('<a href="#" id="save_ExistingCommProfile" title="Save Profile">Save <img src="images/save.gif"></a>');
            }
            
            if (ctype==2)
            {
                $('#ncp_ctype_display').text('Percentage');
                $('#ncp_ctype_display').append('<input type="hidden" id="ncp_ctype">');
                $('#ncp_ctype').val(ctype);
                $('#ncp_rwdrate').val(parseFloat(rwdrate));
                $('#line_ncp_rwdamt').hide();
                $('#line_ncp_trgsrc').hide();
                $('#save_CommProfiles').append('<a href="#" id="save_ExistingCommProfile" title="Save Profile">Save <img src="images/save.gif"></a>');
            }
            
            $('#ncp_trgsrcval').val(trgsrcval);
			
			if (ctype==1 && trgsrcval==7) {
				$('#line_ncp_rwdamt').hide();
				$('#line_ncp_trgsrc').hide();
			}
			
            $('#ncp_trgsrc').val(trgsrc);
            
            if (catid==6 || catid==9)
            {
                if (cmid != 0)
                {
                    $('#ncp_trgwght').val(trgwght);
                }
                
                $('#line_ncp_trgwght').show();
            }
            else
            {
                $('#line_ncp_trgwght').hide();
                $('#savetierenable').append('<a href="#" id="del_comm_profile" title="Delete Commission Profile"><img src="../images/delete.png"> Delete</a>');
            }
        }
        else
        {
            $('#line_ncp_active').hide();
            $('#line_ncp_cmid').hide();
            $('#line_ncp_rwdamt').hide();
            $('#line_ncp_rwdrate').hide();
            $('#line_ncp_trgwght').hide();
            $('#line_ncp_trgsrc').hide();
            $('#line_ncp_trgsrcval').hide();
        }
    }
    
    $('#del_comm_profile').live('click',function(event){
        event.preventDefault();
        
        var tconfirm = confirm('Click OK to Delete this Commission Profile');
        
        if (tconfirm)
        {
            var tdata = new Object();
            tdata.oid=active_office;
            tdata.cmid=parseInt($('#ncp_cmid_display').html());
            tdata.catid=parseInt($('#ncp_catid_display').html());            
            tdata.sid=active_salesr;
            tdata.btype=cbp_view_tab_id;
            tdata.subq='delete_CommProfile';

            delete_CommProfile(tdata);
        }
    });
    
    $('#ncp_ctype').live('change',function(){
        
        trgcat=parseInt($('.cbtype_select').attr('id'));
        
        $('#line_ncp_rwdrate').hide();
        $('#line_ncp_trgsrcval').hide();
        $('#line_ncp_rwdamt').hide();
        $('#line_ncp_trgsrc').hide();
        $('#save_CommProfiles').empty();
        
        //alert(trgcat);
        
        if ($('#ncp_ctype').val()==1)
        {
            $('#line_ncp_rwdamt').show();
			$('#line_ncp_trgsrcval').show();
            $('#save_CommProfiles').append('<a href="#" id="save_NewCommProfile"><img src="images/save.gif" title="Save Profile"></a>');
        }
        
        if ($('#ncp_ctype').val()==2)
        {
            $('#line_ncp_rwdrate').show();
            $('#line_ncp_trgsrcval').show();
            $('#save_CommProfiles').append('<a href="#" id="save_NewCommProfile"><img src="images/save.gif" title="Save Profile"></a>');
        }
        
        if (trgcat==6 || trgcat==9)
        {
            $('#line_ncp_trgsrc').show();
        }
    });
    
    $('#ncp_trgsrc').live('change',function(){
        
        //alert($('#ncp_catid').html());
        
        if ($('#ncp_trgsrc').val()!=0 && parseInt($('#ncp_catid_display').html())!=8)
        {
            $('#line_ncp_trgwght').show();
        }
        else
        {
            $('#line_ncp_trgwght').hide();
        }
        
    });
	
	$('#ncp_trgsrcval').live('change',function(){
        
        //alert($('#ncp_catid').html());
        
        if ($('#ncp_trgsrcval').val()!=0 && parseInt($('#ncp_trgsrcval').val())==7)
        {
            $('#line_ncp_rwdamt').hide();
        }
    });	
    
    $('#catid_activate').live('click',function(event){
        event.preventDefault();
        
        var tconfirm = confirm('Click OK to Activate this Commission Profile');
        
        if (tconfirm)
        {
            var tcmid=parseInt($('#ncp_cmid_display').html());
            ChangeProfileStatus(active_office,tcmid,1);
        }
    });
    
    $('#catid_deactivate').live('click',function(event){
        event.preventDefault();
        
        var tconfirm = confirm('Click OK to Deactivate this Commission Profile');
        
        if (tconfirm)
        {
            var tcmid=parseInt($('#ncp_cmid_display').html());
            ChangeProfileStatus(active_office,tcmid,0);
        }
    });
    
    $('#save_ExistingCommProfile').live('click',function(event){
        
        var tconfirm = confirm('Click OK to Update this Commission Profile');
        
        if (tconfirm)
        {
            var obj=new Object();
            obj.oid=active_office;
            obj.cmid=parseInt($('#ncp_cmid_display').html());
            obj.catid=parseInt($('#ncp_catid_display').html());
            obj.ctype=parseInt($('#ncp_ctype').val());
            obj.rwdamt=parseFloat($('#ncp_rwdamt').val());
            obj.rwdrate=parseFloat($('#ncp_rwdrate').val());
            obj.trgsrc=parseInt($('#ncp_trgsrc').val());
            obj.trgsrcval=parseInt($('#ncp_trgsrcval').val());
            obj.trgwght=parseInt($('#ncp_trgwght').val());
            obj.sid=active_salesr;
            obj.btype=cbp_view_tab_id;

            ChangeProfileSettings(obj);
        }
    });
    
    function ChangeProfileSettings(obj)
    {
        var JSONobj = JSON.stringify(obj);

        $.ajax({
            cache:false,
            type : 'POST',
            url : cbpscript,
            data: {
                'call' : 'cbp',
                'subq' : 'ChangeProfileSettings',
                'oid' : obj.oid,
                'cmid' : obj.cmid,
                'odata' : JSONobj,
                'optype': 'json'
            },
            dataType: 'json',
            success: function (data) {
                if (data.result > 0)
                {
                    show_CommProfiles(obj.oid,obj.sid,obj.catid,obj.btype)
                    alert(data.result + ' Record(s) Updated');
                }
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
                alert('Error: ' + errorThrown);
            }
        });
    }
    
    function ChangeProfileStatus(oid,cmid,state)
    {
        $.ajax({
            cache:false,
            type : 'POST',
            url : cbpscript,
            data: {
                'call' : 'cbp',
                'subq' : 'ChangeProfileState',
                'oid' : oid,
                'cmid' : cmid,
                'state' : state,
                'optype': 'json'
            },
            dataType: 'json',
            success: function (data) {
                //alert(data.result);
                if (data.result > 0)
                {
                    if (state==1)
                    {
                        $('#ncp_active_display').text('Yes');
                        $('#ncp_active_display').css('color','green');
                        $('#ncp_active_display').append('<a href="#" class="JMStooltip" id="catid_deactivate" title="Deactivate this Profile"><img src="../images/bullet_wrench.png"></a>');
                        $('.cbtype_select').css('color','green');
                    }
                    
                    if (state==0)
                    {
                        $('#ncp_active_display').text('No');
                        $('#ncp_active_display').css('color','red');
                        $('#ncp_active_display').append('<a href="#" class="JMStooltip" id="catid_activate" title="Activate this Profile"><img src="../images/bullet_wrench.png"></a>');
                        $('.cbtype_select').css('color','red');
                    }
                }
                else
                {
                    alert('No Change Made');
                }
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
                alert('Error: ' + textStatus);
            }
        });
    }
    
    $('#save_NewCommProfile').live('click',function(){
        var tconfirm = confirm('Click OK to Add this Profile');
        
        if (tconfirm)
        {
            var obj=new Object();
            obj.oid=active_office;
            obj.sid=active_salesr;
            obj.catid=parseInt($('#ncp_catid_display').html());
            obj.ctype=parseInt($('#ncp_ctype').val());
            obj.rwdamt=parseFloat($('#ncp_rwdamt').val());
            obj.rwdrate=parseFloat($('#ncp_rwdrate').val());
            obj.trgsrc=parseInt($('#ncp_trgsrc').val());
            obj.trgsrcval=parseInt($('#ncp_trgsrcval').val());
            obj.trgwght=parseInt($('#ncp_trgwght').val());
            obj.btype=cbp_view_tab_id;
            
            save_NewCommProfile(obj);
            show_BasicReqsCheck(obj.btype);
        }
    });
    
    function save_NewCommProfile(obj)
    {
        var JSONobj=JSON.stringify(obj);

        if (obj.catid!=0)
        {
            if (obj.ctype==1 && isNaN(obj.rwdamt))
            {
               alert('Fixed Amount is not a valid number.\n\nCorrect and re-submit.');
               return;
            }
            
            if (obj.ctype==2 && isNaN(obj.rwdrate))
            {
               alert('Percentage Rate is not a valid number.\n\nCorrect and re-submit.');
               return; 
            }
            
            $.ajax({
                cache:false,
                type : 'POST',
                url : cbpscript,
                data: {
                    'call' : 'cbp',
                    'subq' : 'save_NewCommProfile',
                    'oid'  : obj.oid,
                    'odata'  : JSONobj,
                    'optype': 'json'
                },
                dataType: 'json',
                success: function (data) {
                    if (data.result > 0)
                    {
                        show_CommProfiles(obj.oid,obj.sid,obj.catid,obj.btype)
                    }
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        }
    }
    
    $('#new_tier_save').live('click',function()
    {        
        var pdata=new Object();
        pdata.oid=active_office;
        pdata.pcmid=parseInt($('#new_tier_pcmid').html());
        pdata.nr=parseFloat($('#new_tier_rwdrate').val());
        pdata.na=parseFloat($('#new_tier_rwdamt').val());
        pdata.nt=parseInt($('#new_tier_trgwght').val());
        pdata.cmid=parseInt($('#ncp_cmid_display').html());
        pdata.catid=parseInt($('#ncp_catid_display').html());
        pdata.ctype=parseInt($('#ncp_ctype').val());
        pdata.rwdamt=parseFloat($('#ncp_rwdamt').val());
        pdata.rwdrate=parseFloat($('#ncp_rwdrate').val());
        pdata.trgsrc=parseInt($('#ncp_trgsrc').val());
        pdata.trgsrcval=parseInt($('#ncp_trgsrcval').val());
        pdata.trgwght=parseInt($('#ncp_trgwght').val());
        pdata.sid=active_salesr;
        pdata.btype=cbp_view_tab_id;
        
        //if (nr=='NaN' || nr<=0 || rateexists(active_office,cp,nr))
        alert(pdata.nr);
        
        if (isNaN(pdata.nr))
        {
            alert('Percentage Rate Error' + val_err_txt);
        }
        else
        {
            //alert('New Tier Save 2');
            //if (nt=='NaN' || nt<=0 || weightexists(active_office,cp,nt))
            if (isNaN(pdata.nt))
            {
                alert('Weight Error' + val_err_txt);
            }
            else
            {
                save_NewCommTier(pdata);
                show_BasicReqsCheck(pdata.btype);
            }
        }
        
        return;
    });
    
    function save_NewCommTier(obj)
    {
        //alert(obj.oid +':' + obj.pcmid + ':' + obj.nr + ':' + obj.nt);
    
        if (!isNaN(obj.pcmid))
        {
            var objtxt=JSON.stringify(obj);
            
            $.ajax({
                cache:false,
                type : 'POST',
                url : cbpscript,
                data: {
                    'call' : 'cbp',
                    'subq' : 'save_NewCommTier',
                    'oid'  : obj.oid,
                    'odata'  : objtxt,
                    'optype': 'json'
                },
                dataType: 'json',
                success: function (data) {
                    if (data.result > 0)
                    {
                        show_CommProfiles(obj.oid,obj.sid,obj.catid,obj.btype)
                    }
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        }
        else
        {
            alert('Error on save: CMID=' + obj.cmid);
        }
    }
    
    $('.del_existing_cp_tier').live('click',function(){
        
        var tconfirm = confirm('Click OK to Delete this Commission Profile Tier');
        
        if (tconfirm)
        {
            var tdata = new Object();
            tdata.oid=active_office;
            tdata.cmid=parseInt($('#ncp_lcmid').html());
            tdata.catid=parseInt($('#ncp_catid_display').html());            
            tdata.sid=active_salesr;
            tdata.btype=cbp_view_tab_id;
            tdata.subq='delete_CommTier';

            delete_CommProfile(tdata);
            show_BasicReqsCheck(tdata.btype);
        }
    });
    
    function delete_CommProfile(obj)
    {
        //alert(obj.oid + ':' + obj.cmid + ':' + obj.catid + ':' + obj.sid + ':' + obj.btype);
        
        if (!isNaN(obj.cmid))
        {            
            $.ajax({
                cache:false,
                type : 'POST',
                url : cbpscript,
                data: {
                    'call' : 'cbp',
                    'subq' : obj.subq,
                    'oid'  : obj.oid,
                    'cmid'  : obj.cmid,
                    'optype': 'json'
                },
                dataType: 'json',
                success: function (data) {
                    if (data.result > 0)
                    {
                        show_CommProfiles(obj.oid,obj.sid,obj.catid,obj.btype);
                        show_BasicReqsCheck(obj.btype);
                    }
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        }
        else
        {
            alert('Error on delete: CMID=' + obj.cmid);
        }
    }
    
    function get_CommProfilesJSON(oid,sid,ctgry,renov)
    {
        $('#CommBuildEditSpace').append(spinnerIMG);
        //alert(oid + ':' + sid + ':' + cbpt);
        $.ajax({
            cache:false,
            type : 'GET',
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
                $('#spinnerIMG').remove();
                
                if (data.profiles && typeof data.profiles=='object')
                {
                    cp_data=data.profiles;
                    show_cpitem(cp_data,ctgry);
                }
                else
                {
                    var pel='#CommBuildEditSpace';                    
                    $(pel).append(show_cpitem_single_blank(null,ctgry,pel));
                }
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
                alert('Error: ' + textStatus);
            }
        });
    }

    function show_CommProfiles(oid,sid,catid,btype)
    {
        $('#CommBuildEditSpace').empty();
        get_CommProfilesJSON(oid,sid,catid,btype);
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
        $('#CommBuildEditSpace').html('<b>Workspace</b>');
        
        var catid=$(this).attr('id');
        var srsid=$('#CommBuildSRList ul li.active_salesr_select').attr('id');
        var cbtid=parseInt($.cookie("cbp_view_menu_tab"));
        
        $.cookie("active_salesr", srsid);
        
        //$('#CommBuildEditSpace').html('<b>' + $(this).html() + '</b> Commission settings for <b>' + $('#CommBuildSRList ul li.active_salesr_select').html() + '</b>');
        show_CommProfiles(active_office,srsid,catid,cbtid);
        show_BasicReqsCheck(cbp_view_tab_id);
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
        $('#CommBuildEditSpace').html('<b>Workspace</b>');
        
        var catid=$('#CommBuildTypeList ul li.cbtype_select').attr('id');
        var srsid=$(this).attr('id');
        var cbtid=parseInt($.cookie("cbp_view_menu_tab"));
        
        //alert(srsid);
        
        $.cookie("active_salesr", srsid);
        active_salesr=srsid;
        
        //$('#CommBuildEditSpace').html('<b>' + $('#CommBuildTypeList ul li.cbtype_select').html() + '</b> Commission settings for <b>' + $(this).html() + '</b>');
        
        show_CommProfiles(active_office,srsid,catid,cbtid);
        show_BasicReqsCheck(cbp_view_tab_id);
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
    
    $('.active_salesr').live('mouseover mouseout',function(event){
        if (event.type=='mouseover')
        {
            $(this).css("cursor", "pointer");
            $(this).addClass("cbtype_hover");
        }
        
        if (event.type=='mouseout')
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
    
    function getSalesReps(oid)
    {
        var out;
        
        $.ajax({
            cache:false,
            type : 'GET',
            url : usrscript,
            dataType : 'html',
            data: {
                call : 'user',
                subq : 'get_SalesRepsJSON',
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
});