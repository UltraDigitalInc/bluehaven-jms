$(document).ready(function()
{
	var brswr=$.browser;
	if (brswr.msie && (brswr.version == '6.0' || brswr.version == '7.0')){
        alert('NOTICE\n\nYou are using an outdated version of Internet Explorer.\n\nMany features will not function correctly until you upgrade to least Internet Explorer 8 or higher.\n')
	}
});