function AddNetworkFormValidate()
{
	if (document.getElementById('cpname').value.length==0)
	{
		alert ('Company Name is Empty');
		return false;
	}
	else
	{
		if (document.getElementById('cemail').value.length==0)
		{
			alert ('Email is Empty');
			return false;
		}
		else
		{
			return true;
		}
	}
}