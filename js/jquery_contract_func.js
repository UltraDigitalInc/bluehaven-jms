$(document).ready(function()
{
    var pidprocr	= 'QB/bhsoap/QB_process_PID.php';
	var ajxjobscript    = 'subs/ajax_job_req.php';
    var spinnerHTML = '<img src="images/mozilla_blu.gif"> Sending...';
	
	$('#sandcblock').live('change',function(e){
		var scbox=($(this).is(':checked'))?1:0;		
		$.post(ajxjobscript,
        {
            call : 'job',
            subq : 'set_SandC',
            usr_oid : $('#active_office').val(),
            usr_jobid : $('#usr_jobid').val(),
			usr_sandc: scbox,
            optype : 'table'
        });
	});
});