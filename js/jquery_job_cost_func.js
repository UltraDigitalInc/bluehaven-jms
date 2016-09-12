$(document).ready(function()
{
    var ajxscript   = 'subs/ajax_job_cost_req.php';
    var expout      = '';
    
    $('button').button();
    
    $('#incCustData').button();
    $('#incRetailData').button();
    $('#incCostData').button();
    
    $('#objExportJob').live('click',function(){
        jobexport('Cost');
    });
    
    $('#objExportCst').live('click',function(){
        jobexport('Customer');
    });
    
    $('#NewExportWindow').live('click',function(e){
        e.preventDefault();
        
        var expwindow =  window.open('','JMSExportWindow','width=1024,height=768,menubar=yes,scrollbars=yes');
        expwindow.document.open();
        expwindow.document.write(expout);
        expwindow.document.close();
        return false;
    });
    
    function countItems(el)
    {
        var out = el.length;
        return out;
    }
    
    function procExport(el)
    {
        var icnt    = countItems(el);
        var ocnt    = 0;
        var hdr     = '<table><tr><td></td></tr>';
        var ftr     = '</table>';
        
        if (icnt > 0)
        {
            expout+=hdr;
            
            $.each(el,function(k,v){
                ocnt++;
                expout+='<tr>';
                expout+='   <td>'+ $(v).html() +'</td>';
                expout+='</tr>';
            });
            
            expout+=ftr;
            
            //alert(expout);
            $('#FileOutLink').append('<a href="#" id="NewExportWindow">Download</a>');
        }
        else
        {
            $('#FileOutLink').append('No items found');
        }
        
        return false;
    }
    
    function jobexport(frtype)
    {        
        var cjobid   = $('#ViewRetail > #costjobid').val();
        var opts     = 'subs/ajax_jobdata_req.php?oid='+ ActiveOffice +'&jid='+ cjobid +'&call=job&subq=expJobData&frtype='+ frtype;
        
        window.open(opts);
        return false;
    }
});