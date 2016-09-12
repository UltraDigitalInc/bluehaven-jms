//
//if (document.getElementById('LdSrchPnls'))
//{
//	//var psSS = $('subpnlSSTRING');
//	//var psSR = $('subpnlSALESREP');
//	
//	new $('subpnlSSTRING').Effect.BlindUp();
//	new $('subpnlSALESREP').Effect.BlindUp();
//}
//
//if (document.getElementById)
//{
//	document.write('<style type="text/css">\n')
//	document.write('.submenu{display: none;}\n')
//	document.write('</style>\n')
//}

function changeBGC(color)
{
	document.bgColor = color;
}

function warnscreensize()
{
	if ((screen.width < 1024) && (screen.height < 768))
	{
		alert('Your screen resolution is too small to properly view the JMS. Resize it to 1024 x 768 or greater for best results. Contact BHNM IT Support for assistance: 619-233-3522 x10180');
	}
}

function CopyLeadFields()
{
	if (document.getElementById('Ssame').checked)
	{
		//alert('values: ' + document.getElementById('CurrAddress').value);
		document.getElementById('SiteAddress').value = document.getElementById('CurrAddress').value;
		document.getElementById('SiteCity').value = document.getElementById('CurrCity').value;
		document.getElementById('SiteState').value = document.getElementById('CurrState').value;
		document.getElementById('SiteZip').value = document.getElementById('CurrZip').value;
	}
	else
	{
		document.getElementById('SiteAddress').value = '';
		document.getElementById('SiteCity').value = '';
		document.getElementById('SiteState').value = '';
		document.getElementById('SiteZip').value = '';
	}
	
	return true;
}

function formatPhoneField(idata)
{
	var theCount = 0;
	var oNam = document.getElementById(idata).name;
	var iStr = document.getElementById(idata).value;
	var iLen = iStr.length;
	var mStr = iStr;
	var nStr = '';
	var aStr = '';
	
	if (iStr.length > 0)
	{
		for ( var i = 0 ; i < iStr.length ; i++ )
		{
			// Character codes for ints 1 - 9 are 48 - 57
			if ( (mStr.charCodeAt(i) >= 48 ) && (mStr.charCodeAt(i) <= 57) )
			{
				nStr = nStr + mStr.charAt(i);
			}
		}
		
		if (nStr.length == 10)
		{
			var nData = '';
			for ( var i = 0 ; i < nStr.length ; i++ )
			{
				if ( ( i == 2 ) || ( i == 5 ) )
				{
					nData = nData + nStr.charAt(i) + '-';
				}
				else
				{
					nData= nData+ nStr.charAt(i);
				}
			}
			document.getElementById(idata).value = nData;
			return true;
		}
		else
		{
			alert(nStr + ' was entered. A complete Phone Number is required in the following format: XXX-XXX-XXXX');
			document.getElementById(idata).focus();
			document.getElementById(idata).select();
			return false;
		}
	}
	else
	{
		return false;
	}
}

function formatZipCode(idata)
{
	var theCount = 0;
	var oNam = document.getElementById(idata).name;
	var iStr = document.getElementById(idata).value;
	var iLen = iStr.length;
	var mStr = iStr;
	var nStr = '';
	var aStr = '';
	
	if (iStr.length == 5)
	{
		for ( var i = 0 ; i < iStr.length ; i++ )
		{
			// Character codes for ints 1 - 9 are 48 - 57
			if ( (mStr.charCodeAt(i) >= 48 ) && (mStr.charCodeAt(i) <= 57) )
			{
				nStr = nStr + mStr.charAt(i);
			}
		}
		
		if (nStr.length == 5)
		{
			var nData = '';
			for ( var i = 0 ; i < nStr.length ; i++ )
			{
				/*if ( ( i == 2 ) || ( i == 5 ) )
				{
					nData = nData + nStr.charAt(i) + '-';
				}
				else
				{*/
					nData= nData+ nStr.charAt(i);
				//}
			}
			document.getElementById(idata).value = nData;
			return true;
		}
		else
		{
			alert(nStr + ' was entered. A complete 5 Digit Zip Code is required.');
			document.getElementById(idata).focus();
			document.getElementById(idata).select();
			return false;
		}
	}
	else
	{
		alert('5 Digit Numerical Zip Code is required.');
		document.getElementById(idata).focus();
		document.getElementById(idata).select();
		return false;
	}
}

function validateEmail(idata)
{
	var vStr = document.getElementById(idata).value;
	
	if (vStr.length > 0)
	{
		emailpat = /^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9])+(\.[a-zA-Z0-9_-]+)+$/;
		
		if(!emailpat.test(vStr))
		{
			alert('You entered ' + vStr + '. A properly formatted email address is required. Otherwise leave the field blank.');
			document.getElementById(idata).focus();
			document.getElementById(idata).select();
			return false;
		}
		else
		{
			return true;
		}
	}
	else
	{
		return false;
	}
}

function validateForm(frmData)
{
	var mnFld = new Array('CustFirstName','CustLastName','CurrAddr','CurrCity','CurrZip');
	var orFld = new Array('HomePhone','WorkPhone','CellPhone','EmailAddr');
	var vForm = document.getElementById(frmData);
	
	//alert(mnFld.length);
	//alert(orFld.length);
	//alert(vForm.length);
	
	var AlertPre ='The following fields require information\nbefore submitting the Lead Form: \n\n'
	var AlertTxtI ='';
	var AlertTxtJ ='';
	var AlertIncr =0;
	for (var i=0;i < vForm.length; i++)
	{
		if (
				vForm.elements[i].id == 'FirstName' ||
				vForm.elements[i].id == 'LastName' ||
				vForm.elements[i].id == 'CurrAddress' ||
				vForm.elements[i].id == 'CurrCity' ||
				vForm.elements[i].id == 'CurrZip'
			)
		{
			if (vForm.elements[i].value.length == 0)
			{
				AlertTxtI += vForm.elements[i].id + '\n';
			}
		}
	}
	
	for (var j=0;j < vForm.length; j++)
	{
		if (
				vForm.elements[j].id == 'HomePhone' ||
				vForm.elements[j].id == 'WorkPhone' ||
				vForm.elements[j].id == 'CellPhone' ||
				vForm.elements[j].id == 'EmailAddr'
			)
		{
			if (vForm.elements[j].value.length > 0)
			{
				AlertIncr++;
			}
		}
	}
	
	if (AlertTxtI.length > 0)
	{
		alert(AlertPre + AlertTxtI);
		return false;
	}
	else
	{
		if (AlertIncr == 0)
		{
			//alert('One of the following requires data: \nHomePhone\nWorkPhone\nCellPhone\nEmailAddr\n' + AlertIncr);
			alert('One of the following requires data: \nHomePhone\nWorkPhone\nCellPhone\nEmailAddr\n');
			return false;
		}
		else
		{
			return true;
		}
	}
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


