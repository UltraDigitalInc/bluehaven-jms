$(document).ready(function()
{
	var ajxscript	= 'subs/ajax_office_req.php';
	var rtrimg		= '<span id="rtrimg"><p><img src="../images/mozilla_blu.gif"> Retrieving...</p></span>';
	var prcimg		= '<span id="prcimg"><p><img src="../images/mozilla_blu.gif"> Processing...<br>This may take several minutes depending on Operations selected.<br>Please do not cancel or refresh page.</p></span>';
	
	$('button').button();
	
	$("#addNewOfficebtn").live('click',function(event) {
        event.preventDefault();		
        displayAddOfficeDialog($(this));
    });
	
	$('#srcoffice').live('change',function(event){
		$('#mvleads').attr('checked', false);
		$('#mvzips').attr('checked', false);
		$('#cpyretail').attr('checked', false);
		$('#cpycost').attr('checked', false);
		$('#cpycomm').attr('checked', false);
		$('#mvleads_res').empty();
		$('#mvzips_res').empty();
		$('#cpyretail_res').empty();
		$('#cpycost_res').empty();
		$('#cpycomm_res').empty();
	});
	
	$('#mvleads').live('click',function(event){
		var isChecked = $(this);
		var srcoff = $('#srcoffice').val();
		
		if (srcoff==0){
			event.preventDefault();
			alert('Source Office must be selected');
		}
		
		if (isChecked.is(':checked') && srcoff!=0)
		{
			var numleads = getOfficeInfo($(this));
			$('#mvleads_res').html(numleads.otext+' Leads will be moved');
		}
		else
		{
			$('#mvleads_res').empty();
		}
	});
	
	$('#mvzips').live('click',function(event){
		var isChecked = $(this);
		var srcoff = $('#srcoffice').val();
		
		if (srcoff==0){
			event.preventDefault();
			alert('Source Office must be selected');
		}
		
		if (isChecked.is(':checked') && srcoff!=0)
		{
			var numzips = getZipMatrix($(this));
			$('#mvzips_res').html(numzips.zcnt+' Entries will be moved');
		}
		else
		{
			$('#mvzips_res').empty();
		}
	});
	
	$('#cpyretail').live('click',function(event){
		var isChecked = $(this);
		var srcoff = $('#srcoffice').val();
		
		if (srcoff==0){
			event.preventDefault();
			alert('Source Office must be selected');
		}
		
		if (isChecked.is(':checked') && srcoff!=0)
		{
			var retpb=getRetailPB($(this));
			$('#cpyretail_res').html(retpb.retail+' Items will be copied');
		}
		else
		{
			$('#cpyretail_res').empty();
		}
	});
    
	$('#cpycost').live('click',function(event){
		var isChecked = $(this);
		var srcoff = $('#srcoffice').val();
		
		if (srcoff==0){
			event.preventDefault();
			alert('Source Office must be selected');
		}
		
		if (isChecked.is(':checked') && srcoff!=0)
		{
			var ccost=getCostCnt($(this));
			$('#cpycost_res').html(ccost.ccnt+' Items will be copied');
		}
		else
		{
			$('#cpycost_res').empty();
		}
	});
	
	$('#cpycomm').live('click',function(event){
		var isChecked = $(this);
		var srcoff = $('#srcoffice').val();
		
		if (srcoff==0){
			event.preventDefault();
			alert('Source Office must be selected');
		}
		
		if (isChecked.is(':checked') && srcoff!=0)
		{
			var comms=getCommsCnt($(this));
			$('#cpycomm_res').html(comms.ccnt+' Profiles will be copied');
		}
		else
		{
			$('#cpycomm_res').empty();
		}
	});
	
    function displayAddOfficeDialog(el)
    {
        $("#AddOfficeDialog").dialog("close").remove();
        $('body').append('<div id="AddOfficeDialog" style="display:none"></div>');
        $('#AddOfficeDialog').append(rtrimg);
		
        var dialog = $("#AddOfficeDialog").dialog({
            title : 'Add New Office',
            autoOpen: false,
            resizable: true,
            modal: true,
            width: 425,
            height: 500,
			open: getAddOfficeForm(el),
            close: closeAddOfficeDialog(),
            position: {
                my: 'right top',
                at: 'right bottom',
                of: el
            },
			buttons: {
				'Save': function(){
					var vflds=getAddOfficeFormValsNew();
					if (testAddOfficeValues(vflds))
					{
						var tc=confirm('Form Validated!\n\nSubmitting Request... please be patient.\nThis process may take a few minutes depending on Operations selected.\n\nClick Ok to continue.');
						if (tc)
						{
							$(".ui-dialog-buttonpane button:contains('Save')").attr("disabled", true).addClass("ui-state-disabled");
							$(".ui-dialog-buttonpane button:contains('Cancel')").attr("disabled", true).addClass("ui-state-disabled");
							procaddOfficeForm(vflds);
						}
					}
				},
				'Cancel': function(){
					closeAddOfficeDialog();
				}
			}
        }).dialog("open");
        
        return false;
    }
	
	function getAddOfficeFormValsNew()
	{
		var iflds = $('.ffldsstat');
		var vflds = {};
		$.each(iflds,function(k1,v1){
			var fname=$(this).attr('id');
			
			if (fname=='mvleads'||fname=='mvzips'||fname=='cpyretail'||fname=='cpycost'||fname=='cpycomm')
			{
				vflds[fname]=($(this).is(':checked'))? $(this).val(): 0;
			}
			else
			{
				vflds[fname]=$(this).val();
			}
		});
		
		return vflds;
	}
	
	function procaddOfficeForm(fvars)
	{
		$('#AddOfficeDialog').empty().append(prcimg);
		$.ajax({
			cache:true,
			type : 'POST',
			url : ajxscript,
			dataType : 'json',
			data : fvars,
			success : function(data){
				$('#prcimg').remove();
				
				var noid=parseInt(data.oidnew);
				if (!isNaN(noid)&& noid!=0)
				{
					$('#AddOfficeDialog').append(data.otext);
					$('#AddOfficeDialog').dialog('option', 'title', 'Add Office Result');
					$('#AddOfficeDialog').dialog('option', 'height', 200);
					$('#AddOfficeDialog').dialog('option', 'buttons', {
						'View Office': function (){
							viewOffice(noid);
							closeAddOfficeDialog();
						}
					});
				}
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				alert(textStatus);
			}
		});
		return false;
	}
    
	function viewOffice(noid)
	{
		var txt='';
		txt+='<form method="post" id="ViewOfficeForm">';
		txt+='<input type="hidden" value="maint" name="action">';
		txt+='<input type="hidden" value="off" name="call">';
		txt+='<input type="hidden" value="view" name="subq">';
		txt+='<input type="hidden" value="'+noid+'" name="officeid">';
		txt+='</form>';
		$('body').append(txt);
		$('#ViewOfficeForm').submit();
		return false;
	}
	
    function getAddOfficeForm(el)
    {
        $.ajax({
			cache:true,
			type : 'POST',
			url : ajxscript,
			dataType : 'html',
			data: {
				call : 'office',
				subq : 'get_AddOfficeForm',
				optype : 'table'
			},
			success : function(data){
				$('#rtrimg').hide();
				$('#AddOfficeDialog').append(data);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				alert(textStatus);
			}
		});
    }
    
    function closeAddOfficeDialog()
    {
        $("#AddOfficeDialog").remove();
        return false;
    }
	
	function testAddOfficeValues(ar)
	{
		var err=0;
		var errtext='Error List:\n';
		
		$.each($('.officeffval'),function(k,v){			   
			var oatr = $(this).attr('id');
			var oval = $(this).val();
			var ov	 = getOfficeInfo($(this));
			
			if (oval.length > 0)
			{
				if (ov.result && ov.oid!=0)
				{
					switch (oatr)
					{
						case 'oname':
							var ftxt='Office Name';	
							break;
						
						case 'ozip':
							var ftxt='Zip Code';
							break;
						
						case 'ophone':
							var ftxt='Phone Number';	
							break;
						
						default:
							var ftxt = '';
							break;
					}
					
					err++;
					errtext+='- '+ftxt+' is already in use in '+ov.otext+'\n';
				}
			}
		});
		
		if (ar['oname'].length < 3)
		{
			err++;
			errtext+='- Name must be filled in, and be 3 or more characters\n';
		}
		
		$.each($('.userffval'),function(k1,v1){			   
			var uatr = $(this).attr('id');		
			var uv=getLogidInfo($(this));
			
			if (uv.result && uv.sid!=0)
			{
				err++;
				errtext+='- '+uv.stext+'\n';
			}
		});
		
		if (ar['oaddr1'].length < 3)
		{
			err++;
			errtext+='- Address 1 must be filled in, and be 3 or more characters\n';
		}
		
		if (ar['ocity'].length < 2)
		{
			err++;
			errtext+='- City must be filled in, and be 2 or more characters\n';
		}
		
		if (ar['ostate']==0)
		{
			err++;
			errtext+='- State must be selected\n';
		}
		
		if (ar['ozip'].length != 5 || isNaN(ar['ozip']))
		{
			err++;
			errtext+='- Zip Code must be 5 numbers\n';
		}
		
		if (ar['ophone'].length != 10 || isNaN(ar['ophone']))
		{
			err++;
			errtext+='- Phone must be 10 numbers, in 9995551212 format\n';
		}
		
		if (ar['gmlogid'].length > 0)
		{
			if (ar['gmlogid'].length < 4 || ar['gmlogid'].length > 8)
			{
				err++;
				errtext+='- Login ID must between 4 and 8 characters\n';
			}
			
			if (ar['gmlogid'] == ar['gmpass'])
			{
				err++;
				errtext+='- Login ID & Password cannot be the same\n';
			}
			
			if (ar['gmpass'].length < 4 || ar['gmpass'].length > 8)
			{
				err++;
				errtext+='- Password must between 4 and 8 characters\n';
			}
			
			if (ar['gmfirst'].length < 1)
			{
				err++;
				errtext+='- First Name must be filled in\n';
			}
			
			if (ar['gmlast'].length < 1)
			{
				err++;
				errtext+='- Last Name must be filled in\n';
			}
		}
		
		if ((ar['mvleads'] || ar['cpypr'] || ar['cpyretail'] || ar['cpycost'] || ar['cpycomm']) && ar['srcoffice']==0)
		{
			err++;
			errtext+='- Source Office must be selected for any Move/Copy Operation\n';
		}
		
		if (err > 0)
		{
			alert(errtext);
			return false;
		}
		else
		{
			return true;
		}
	}
	
	function getAddOfficeFormVal()
	{
		var oAr=new Array();
		oAr['otypecd']	=$('#otypecd').val();
		oAr['oname']	=$('#oname').val();
		oAr['oaddr1']	=$('#oaddr1').val();
		oAr['oaddr2']	=$('#oaddr2').val();
		oAr['ocity']	=$('#ocity').val();
		oAr['ostate']	=$('#ostate').val();
		oAr['ozip']		=$('#ozip').val();
		oAr['ophone']	=$('#ophone').val();
		oAr['gmfirst']	=$('#gmfirst').val();
		oAr['gmlast']	=$('#gmlast').val();
		oAr['gmlogid']	=$('#gmlogid').val();
		oAr['gmpass']	=$('#gmpass').val();
		oAr['mvleads']	=$('#mvleads').is(':checked');
		oAr['cpyretail']=$('#cpyretail').is(':checked');
		oAr['cpycost']	=$('#cpycost').is(':checked');
		oAr['cpycomm']	=$('#cpycomm').is(':checked');
		oAr['srcoffice']=$('#srcoffice').val();

		return oAr;
	}
	
	function getRetailPB(el)
	{
		var tfld = el.attr('id');
		var tval = $('#srcoffice').val();
		
		var pbdata =  (function(){
			var json = null;
			$.ajax({
				'async': false,
				'global': false,
				url: ajxscript,
				dataType: 'json',
				data: {
				   call: 'office',
				   subq: 'get_RetailPB',
				   vval: tval,
				   optype : 'json'
				},
				'success': function (data) {
					json = data;
				}
			});
	
			return json;
		})();
		
		return pbdata;
	}
	
	function getZipMatrix(el)
	{
		var tfld = el.attr('id');
		var tval = $('#srcoffice').val();
		
		var zdata =  (function(){
			var json = null;
			$.ajax({
				'async': false,
				'global': false,
				url: ajxscript,
				dataType: 'json',
				data: {
				   call: 'office',
				   subq: 'get_ZipMatrix',
				   vval: tval,
				   optype : 'json'
				},
				'success': function (data) {
					json = data;
				}
			});
	
			return json;
		})();
		
		return zdata;
	}
	
	function getCommsCnt(el)
	{
		var tfld = el.attr('id');
		var tval = $('#srcoffice').val();
		
		var cbdata =  (function(){
			var json = null;
			$.ajax({
				'async': false,
				'global': false,
				url: ajxscript,
				dataType: 'json',
				data: {
				   call: 'office',
				   subq: 'get_CommsCnt',
				   vval: tval,
				   optype : 'json'
				},
				'success': function (data) {
					json = data;
				}
			});
	
			return json;
		})();
		
		return cbdata;
	}
	
	function getCostCnt(el)
	{
		var tfld = el.attr('id');
		var tval = $('#srcoffice').val();
		
		var cbdata =  (function(){
			var json = null;
			$.ajax({
				'async': false,
				'global': false,
				url: ajxscript,
				dataType: 'json',
				data: {
				   call: 'office',
				   subq: 'get_CostCnt',
				   vval: tval,
				   optype : 'json'
				},
				'success': function (data) {
					json = data;
				}
			});
	
			return json;
		})();
		
		return cbdata;
	}
	
	function getOfficeInfo(el)
	{
		var tfld = el.attr('id');
		
		if (tfld=='mvleads')
		{
			var tval = $('#srcoffice').val();
		}
		else
		{
			var tval = el.val();
		}
		
		var offdata =  (function(){
			var json = null;
			$.ajax({
				'async': false,
				'global': false,
				url: ajxscript,
				dataType: 'json',
				data: {
				   call: 'office',
				   subq: 'get_OfficeInfo',
				   ffld: tfld,
				   vval: tval,
				   optype : 'json'
				},
				'success': function (data) {
					json = data;
				}
			});
	
			return json;
		})();
		
		return offdata;
	}
	
	function getLogidInfo(el)
	{
		var tval = el.val();
		
		var usrdata =  (function(){
			var json = null;
			$.ajax({
				'async': false,
				'global': false,
				url: ajxscript,
				dataType: 'json',
				data: {
				   call: 'office',
				   subq: 'get_UserInfo',
				   vval: tval,
				   optype : 'json'
				},
				'success': function (data) {
					json = data;
				}
			});
	
			return json;
		})();
		
		return usrdata;
	}
	
});