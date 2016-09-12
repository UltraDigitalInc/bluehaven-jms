
function ElemValue(e)
{
	alert(document.getElementById(e).value);
}

function GetXmlHttpObject()
{
	if (window.XMLHttpRequest)
	{
	  // code for IE7+, Firefox, Chrome, Opera, Safari
	  return new XMLHttpRequest();
	}
	if (window.ActiveXObject)
	{
	  // code for IE6, IE5
	  return new ActiveXObject("Microsoft.XMLHTTP");
	}
	return null;
}

function detectItem(originalArray, itemToDetect)
{
	var j = 0;
	while (j < originalArray.length)
	{
		if (originalArray[j] == itemToDetect)
		{
			return true;
		}
		else
		{
			j++;
		}		
	}
	return false;
}

function removeItem(originalArray, itemToRemove) {
	var j = 0;
	while (j < originalArray.length)
	{
		//	alert(originalArray[j]);
		if (originalArray[j] == itemToRemove)
		{
			originalArray.splice(j, 1);
		}
		else
		{
			j++;
		}
	}
	//	assert('hi');
	return originalArray;
}

function CopytoClipBoard(e,h)
{
		//alert(document.getElementById(e).innerHTML);
		document.getElementById(h).innerText = e;
		Copied = document.getElementById(h).createTextRange();
		Copied.execCommand("Copy");
		
		alert(e + ' copied to Clipboard')
}

function ClearField(e)
{
	//var fld = document.getElementById(e);
	document.getElementById(e).value='';
}

function BasicFormCheck(frm,errtxt)
{
	var elem = document.getElementById(frm).elements;
	var str = '';
	if (frm == 'NewOffice')
	{
		var skip = 'Addr2';
		//var skipAr = ['Addr2','Fax'];
	}
	else
	{
		var skip = '';
		//var skipAr = '';
	}
	
	for(var i = 0; i < elem.length; i++)
	{
		if (elem[i].name!=skip)
		//if (!skipAr.indexOf(elem[i].name))
		{
			if (elem[i].type=='text' && elem[i].value.length==0)
			{
				//alert (elem[i].name + ' is empty')
				str += '<font color="red"><b>Error!</b></font>  <b>' + elem[i].name + '</b> is Empty';
				document.getElementById(errtxt).innerHTML = str;
				return false;
			}
		}
		
		if (elem[i].type=='select-one' && elem[i].value==0)
		{
			//alert (elem[i].name + ' required')
			str += '<font color="red"><b>Error!</b></font>  <b>' + elem[i].name + '</b> is Required';
			document.getElementById(errtxt).innerHTML = str;
			return false;
		}
	}
	return true;
}

function FormCheckValues(frm,errtxt)
{
	var elem = document.getElementById(frm).elements;
	var str = '';

	for (var i = 0; i < elem.length; i++)
	{
		if (elem[i].name!='fileattach')
		{
			if (elem[i].value.length==0)
			{
				//alert (elem[i].name);
				str += '<font color="red"><b>Error!</b></font>  <b>' + elem[i].name + '</b> is Blank';
				document.getElementById(errtxt).innerHTML = str;
				return false;
			}
		}
	}
	
	return true;
}

function VerifyLeadForm()
{
	var str = 'The following field(s) are incomplete or incorrect:\n';
	var err = 0;
	
	var cfn = document.getElementById('cfname');
	var cln = document.getElementById('clname');
	var cho = document.getElementById('chome');
	var cce = document.getElementById('ccell');
	var cwo = document.getElementById('cwork');
	var cem = document.getElementById('cemail');
	var cad = document.getElementById('caddr1');
	var cci = document.getElementById('ccity');
	var czi = document.getElementById('czip1');
	var con = document.getElementById('cconph');
	var src = document.getElementById('source');
	var iem = document.getElementById('intro_email');
	
	var adm = document.getElementById('appt_mo');
	var add = document.getElementById('appt_da');
	var ady = document.getElementById('appt_yr');
	var adh = document.getElementById('appt_hr');
	var adn = document.getElementById('appt_mn');
	
	//alert(cfn.value);

	if (cfn.value.length==0)
	{
		str = str + 'First Name\n';
		err++;
	}
	
	if (cln.value.length==0)
	{
		str = str + 'Last Name\n';
		err++;
	}
	
	if (con.value=='hm')
	{
		if (cho.value.length==0)
		{
			str = str + 'Home Phone\n';
			err++;
		}
	}
	
	if (con.value=='wk')
	{
		if (cwo.value.length==0)
		{
			str = str + 'Work Phone\n';
			err++;
		}
	}
	
	if (con.value=='ce')
	{
		if (cce.value.length==0)
		{
			str = str + 'Cell Phone\n';
			err++;
		}
	}

	if (cem.value.length==0)
	{
		str = str + 'Email\n';
		err++;
	}
	
	if (!EmailValidateCheck(cem.value) && cem.value!='NA')
	{
		str = str + 'Email Invalid\n';
		err++;
	}
	
	if (cad.value.length==0)
	{
		str = str + 'Street\n';
		err++;
	}
	
	if (cci.value.length==0)
	{
		str = str + 'City\n';
		err++;
	}
	
	if (czi.value.length < 5)
	{
		str = str + 'Zip\n';
		err++;
	}
	
	if (src.value < 2)
	{
		str = str + 'Lead Source\n';
		err++;
	}
	
	if (adm.value != 0 || add.value != 0 || ady.value != 0 || adh.value != 0)
	{
		if (adm.value == 0)
		{
			str = str + 'Appointment Month Invalid\n';
			err++;
		}
		
		if (add.value == 0)
		{
			str = str + 'Appointment Day Invalid\n';
			err++;
		}
		
		if (ady.value == 0)
		{
			str = str + 'Appointment Year Invalid\n';
			err++;
		}
		
		if (adh.value == 0)
		{
			str = str + 'Appointment Hour Invalid\n';
			err++;
		}
	}
	
	if (err > 0)
	{
		alert(str);
		return false;
	}
	else
	{
		return true;
	}
}

function VerifyLeadFormUpdate()
{
	var str = 'The following field(s) are incomplete or incorrect:\n';
	var err = 0;
	
	var cfn = document.getElementById('cfname');
	var cln = document.getElementById('clname');
	var cho = document.getElementById('chome');
	var cce = document.getElementById('ccell');
	var cwo = document.getElementById('cwork');
	var cem = document.getElementById('cemail');
	var cad = document.getElementById('caddr1');
	var cci = document.getElementById('ccity');
	var czi = document.getElementById('czip1');
	var con = document.getElementById('cconph');
	var src = document.getElementById('source');
	var iem = document.getElementById('intro_email');
	
	var adm = document.getElementById('appt_mo');
	var add = document.getElementById('appt_da');
	var ady = document.getElementById('appt_yr');
	var adh = document.getElementById('appt_hr');
	var adn = document.getElementById('appt_mn');
	
	if (adm.value != 0 || add.value != 0 || ady.value != 0 || adh.value != 0)
	{
		if (adm.value == 0)
		{
			str = str + 'Appointment Month Invalid\n';
			err++;
		}
		
		if (add.value == 0)
		{
			str = str + 'Appointment Day Invalid\n';
			err++;
		}
		
		if (ady.value == 0)
		{
			str = str + 'Appointment Year Invalid\n';
			err++;
		}
		
		if (adh.value == 0)
		{
			str = str + 'Appointment Hour Invalid\n';
			err++;
		}
	}
	
	if (err > 0)
	{
		alert(str);
		return false;
	}
	else
	{
		return true;
	}
}

function FormSubmit(frm)
{
}

function EmailValidateCheck(str)
{
	var at="@"
	var dot="."
	var lat=str.indexOf(at)
	var lstr=str.length
	var ldot=str.indexOf(dot)
	
	if (str.indexOf(at)==-1){
	   //alert("Invalid E-mail ID")
	   return false
	}
	
	if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
	   //alert("Invalid E-mail ID")
	   return false
	}
	
	if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
		//alert("Invalid E-mail ID")
		return false
	}
	
	if (str.indexOf(at,(lat+1))!=-1){
	   //alert("Invalid E-mail ID")
	   return false
	}
   
	if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
	   //alert("Invalid E-mail ID")
	   return false
	}
   
	if (str.indexOf(dot,(lat+2))==-1){
	   //alert("Invalid E-mail ID")
	   return false
	}
   
	if (str.indexOf(" ")!=-1){
	   //alert("Invalid E-mail ID")
	   return false
	}
   
	return true;
}

function CheckFormElements(frm)
{
	var str = 'Name  :  Value  : Type\n';
	var elem = document.getElementById(frm).elements;
	for(var i = 0; i < elem.length; i++)
	{
		str += elem[i].name + " : " + elem[i].value + " : " + elem[i].type + "\n";
	} 
	//document.getElementById('lblValues').innerHTML = str;
	alert(str);
	return false;
}

/*
function PreLoadPage()
{
	if (document.getElementById)
	{
		document.getElementById('prepage').style.visibility='hidden';
	}
	else
	{
		if (document.layers)
		{ //NS4
			document.prepage.visibility = 'hidden';
		}
		else
		{ //IE4
			document.all.prepage.style.visibility = 'hidden';
		}
	}
}
*/

function AddnAlert(elem1,elem2,elem3,elem4)
{
	var e1=document.getElementById(elem1).value;
	var e2=document.getElementById(elem2).value;
	var e3=document.getElementById(elem3).value;
	var e4=document.getElementById(elem4).value;
	
	if (e1 > 0 || e2 > 0 || e3!=e4)
	{
		var agree = confirm('ATTENTION!\n\nYou have modified the Commission for this Addendum.\n\nClick OK to verify the Commission changes.\nClick CANCEL if you do not wish to make this change');
		
		if (agree)
		{
			return true;
		}
		else
		{
			return false;
		}		
	}
	else
	{
		return true;
	}
}

function NoSelectAlert(e,t)
{
	if (document.getElementById(e).value == 0)
	{
		alert('Attention!\n\n' + t + ' selection Invalid.\n\nMake a proper selection');
		return false;
	}
	return true;	
}

function EmptyFieldAlert(e)
{
	if (document.getElementById(e).value == '')
	{
		alert('Attention! Enter a Comment before submitting.\n\n');
		return false;
	}
	return true;	
}

function EmptyString(e)
{
	if (document.getElementById(e).value == '')
	{
		alert('Attention! Enter a Search String before submitting.\n\n');
		return false;
	}
	return true;	
}

function EmptyFolderAdd(e)
{
	if (document.getElementById(e).value == '')
	{
		alert('Attention! Enter a Folder Name before submitting.\n\n');
		return false;
	}
	return true;	
}

function ConfirmChecked(e)
{
	if (document.getElementById(e).checked==false)
	{
		alert('Attention!\n\nCheck the Confirm checkbox to continue');
		return false;
	}
	return true;
}

function CopyWarning(e1,t)
{
	var n=document.getElementById(e1).value;
	
	if (n > 0)
	{
		var agree = confirm('Attention!\n\nThis operation will copy ' + t + ' from the selected Office, and deactivate any current Commission Profiles\n\nClick OK to continue or CANCEL stop the operation');
		
		if (agree)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	else
	{
		return true;
	}
}

function FileUploadCheck(e1,e2,t1,t2)
{
	if (document.getElementById(e1).value == 0)
	{
		alert('Attention!\n\n' + t1 + ' selection Invalid.\nMake a proper selection');
		return false;
	}
	
	if (document.getElementById(e2).value == '')
	{
		alert('Select a File for upload!');
		return false;	
	}
	
	return true;
}

function FolderCheck(e1,e2)
{
	var fld =document.getElementById(e2).value;
	
	if (document.getElementById(e2).value == '')
	{
		alert('Input a Folder Name!');
		return false;
	}
	else
	{
		var agree = confirm('You are are attempting to add Folder ' + fld + '\n\nClick OK to continue or CANCEL to stop');
		
		if (!agree)
		{
			return false;
		}
			
		return true;
	}
}

function ConfirmDelete()
{
	var agree = confirm('You are are attempting to Archive this record\n\nClick OK to continue or CANCEL stop the Archive process');

	if (agree)
	{
		return true;
	}
	
	return false;
}

function ConfirmDeactivateFile()
{
	var agree = confirm('You are are attempting to Deactivate this File.\n\nDeactivated Files are automatically purged from the System permanently after 24 Hours.\n\nClick OK to continue or CANCEL stop');

	if (agree)
	{
		return true;
	}
	
	return false;
}

function ConfirmDeleteFile()
{
	var agree = confirm('You are are attempting to Delete this File\n\nClick OK to continue or CANCEL stop');

	if (agree)
	{
		return true;
	}
	
	return false;
}

function ConfirmRestoreFile()
{
	var agree = confirm('You are are attempting to Restore this File\n\nClick OK to continue or CANCEL stop');

	if (agree)
	{
		return true;
	}
	
	return false;
}

function ConfirmRestore()
{
	var agree = confirm('You are are attempting to Restore this record\n\nClick OK to continue or CANCEL stop the Restore process');

	if (agree)
	{
		return true;
	}
	
	return false;
}

function CreateContractAlerts()
{
	if (document.getElementById('cdate').value.length == 0)
	{
		//document.createcontract.cdate.focus();
		alert('ATTENTION!\n\nContract Date missing or invalid');
		return false;
	}
	
	if (document.getElementById('finan').value == 0)
	{
		alert('ATTENTION!\n\nFinancing method not selected for this Customer');
		return false;
	}
	
	if (document.getElementById('ouo0').value != 0 &&
		document.getElementById('manadjnote').value.length == 0
		)
	{
		alert('ATTENTION!\n\nA Note is required when setting a Manual Commission Adjust');
		return false;
	}
	
	if (document.getElementById('ps_calc').value == 0)
	{
		alert('ATTENTION!\n\nThe Payment Schedule for this Customer has not been Processed.\nClick the Calculate button to update the Payment Schedule');
		return false;
	}
	
	return true;
}

function ElemValueChange(e,s)
{
	document.getElementById(e).value=s;
}

function SRChangeAlert(sid1,sid2,cnt)
{
	var vsid1=document.getElementById(sid1).value;
	var vsid2=document.getElementById(sid2).value;
	var vcnt=document.getElementById(cnt).value;
	
	if (vcnt > 0 && vsid1!=vsid2)
	{
		var agree = confirm('NOTE!\nYou have changed the SalesRep for this Customer.\nClick OK to change all Estimates/Quotes for this Customer to the new SalesRep.\nClick CANCEL if you do not intend to change the SalesRep');
		
		if (agree)
		{
			return true;
		}
		else
		{
			return false;
		}		
	}
	else
	{
		return true;
	}
}

if (document.getElementById)
{
	document.write('<style type="text/css">\n')
	document.write('.submenu{display: none;}\n')
	document.write('</style>\n')
}

function setLocalTime()
{
	var currentDate = new Date()
	var day = currentDate.getDate()
	var month = currentDate.getMonth() + 1
	var year = currentDate.getFullYear()
	
	var hours = currentDate.getHours()
	var minutes = currentDate.getMinutes()

	var suffix = "AM";
	if (hours >= 12)
	{
		suffix = "PM";
		hours = hours - 12;
	}
	
	if (hours == 0)
	{
		hours = 12;
	}
	
	if (minutes < 10)
	{
		minutes = "0" + minutes
	}

	document.write(month + "/" + day + "/" + year + " " + hours + ":" + minutes + " " + suffix)
}

function calcAmtToPercPS(amt1,amt2,out1,t1)
{
	var a1 = document.getElementById(amt1);
	var a2 = document.getElementById(amt2);
	var o1 = document.getElementById(out1);
	
	var v = (a1.value / a2.value) * 100;
	
	if (t1 == 'iH')
	{
		return o1.innerHTML=v.toFixed(0);
	}
	else
	{
		return o1.value=v.toFixed(0);
	}
}

function calcPercToAmtPS(amt1,amt2,amt3,amt4,out1,t1)
{
	var a1 = document.getElementById(amt1);
	var a2 = document.getElementById(amt2);
	var a3 = document.getElementById(amt3);
	var a4 = document.getElementById(amt4);
	var o1 = document.getElementById(out1);

	var v = parseFloat(a1.value) * ((parseFloat(a2.value) - (parseFloat(a3.value) + parseFloat(a4.value))) * .01);
	
	if (t1 == 'iH')
	{
		return o1.innerHTML=v.toFixed(2);
	}
	else
	{
		return o1.value=v.toFixed(2);
	}
}

function TestAmount(in1)
{
	var p = document.getElementById(in1); // Amount
	
	if (parseFloat(p.value) < 1)
	{
		return true;	
	}
	else
	{
		return false;
	}
}

function DispPercRes(pel,sel,oel)
{
	var p = document.getElementById(pel); // Percent Amount
	var s = document.getElementById(sel); // Static Amount
	var o = document.getElementById(oel); // Output Amount
	
	
	var v = parseFloat(s.value) * (parseFloat(p.value) * .01);
	return o.value=v.toFixed(2);
}

function incPerc(pel,sel,oel,ael)
{
	var p = document.getElementById(pel); // Percent Amount
	var s = document.getElementById(sel); // Static Amount
	var o = document.getElementById(oel); // Output Amount
	
	if (ael=='inc')
	{
		p.value++;
	}
	else
	{
		p.value--;
	}
	
	//alert(s.value);
	
	var v = parseFloat(s.value) * (parseFloat(p.value) * .01);
	return o.value=v.toFixed(2);
}

function updPerc(stat,perc,outp)
{
	var s = document.getElementById(stat); // Static
	var p = document.getElementById(perc); // Percent
	var o = document.getElementById(outp); // Output
	var v = (parseFloat(s.value) * (parseFloat(p.value) * .01));

	return o.innerHTML=v.toFixed(2);
	//return o.innerText=v.toFixed(2);
}

function updPercAddn(stat,perc,outp1,outp2)
{
	var s = document.getElementById(stat); // Static
	var p = document.getElementById(perc); // Percent
	var o1 = document.getElementById(outp1); // Output
	var o2 = document.getElementById(outp2); // Output
	var v = (parseFloat(s.value) * (parseFloat(p.value) * .01));

	o1.innerHTML=v.toFixed(2);
	o2.innerText=v.toFixed(2);
	//return o.innerText=v.toFixed(2);
}

function updTotalComm(a0,a1,a2,a3,a4,o1)
{
	var am0 = document.getElementById(a0); // Amount 1 value
	var am1 = document.getElementById(a1); // Amount 2 value
	var am2 = document.getElementById(a2); // Amount 3 value
	var am3 = document.getElementById(a3); // Amount 4 innerText
	var am4 = document.getElementById(a4); // Amount 45 innerText
	var ou1 = document.getElementById(o1); // Result

	//alert(am3.innerText);

	var v = parseFloat(am0.value) + parseFloat(am1.value) + parseFloat(am2.value) + parseFloat(am3.innerText) + parseFloat(am4.innerText);
	
	//	alert(v);
	
	return ou1.innerText=v.toFixed(2);
}

function displayPopup(el1,el2,el3,el4)
{
	if (document.getElementById(el1).value!=0)
	{
		var w=window.open('http://jms.bhnmi.com/subs/previewtemp.php?etid=' + document.getElementById(el1).value + '&cid=' + el4 + '&oid=' + el2 + '&sid=' + el3,'JMSChild_EmailViewer','HEIGHT=400,WIDTH=550,status=0,scrollbars=1,dependent=1,resizable=0,toolbar=0,menubar=0,location=0,directories=0');
		w.focus();
		return true;
	}
	else
	{
		if (el1=='etid')
		{
			alert('Select an appropriate Template and try again.');
		}
		
		return false;
	}
}

function displayPopup_OLD(el1,el2,el3,el4)
{
	if (document.getElementById(el1).value!=0)
	{
		window.open('http://jms.bhnmi.com/subs/previewtemp.php?etid=' + document.getElementById(el1).value + '&cid=' + el4 + '&oid=' + el2 + '&sid=' + el3,'JMSChild_EmailViewer','HEIGHT=400,WIDTH=550,status=0,scrollbars=1,dependent=1,resizable=0,toolbar=0,menubar=0,location=0,directories=0');
		return true;
	}
	else
	{
		if (el1=='etid')
		{
			alert('Select an appropriate Template and try again.');
		}
		
		return false;
	}
}

function displayJobCompletePopup()
{
	alert('This Job has been processed. You cannot take it out of MAS Ready');	
	return false;
}

function SwitchMenu(obj)
{
	if(document.getElementById)
	{
		var el = document.getElementById(obj);
		var ar = document.getElementById("masterdiv").getElementsByTagName("span");
		if(el.style.display != "block")
		{
			for (var i=0; i<ar.length; i++)
			{
				if (ar[i].className=="submenu")
				{
					ar[i].style.display = "none";
				}
			}
			el.style.display = "block";
		}
		else
		{
			el.style.display = "none";
		}
	}
}

function SetAllCheckBoxes(FormName, FieldName, CheckValue)
{
	if(!document.forms[FormName])
		return;
	var objCheckBoxes = document.forms[FormName].elements[FieldName];
	if(!objCheckBoxes)
		return;
	var countCheckBoxes = objCheckBoxes.length;
	if(!countCheckBoxes)
		objCheckBoxes.checked = CheckValue;
	else
		// set the check value for all check boxes
		for(var i = 0; i < countCheckBoxes; i++)
			objCheckBoxes[i].checked = CheckValue;
}


