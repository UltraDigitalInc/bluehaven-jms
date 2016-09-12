$(document).ready(function()
{
    var SalesCart_menu_tab_id   = parseInt($.cookie("SalesCart_menu_tab")) || 0;
    var ajxscript               = 'subs/ajax_sales_req.php';
    var retrIMG                 = '<img src="images/mozilla_blu.gif"> Retrieving...';
    var procIMG                 = '<img src="images/mozilla_blu.gif"> Processing...';
    var spinner                 = '<img src="images/mozilla_blu.gif">';
    
    //var oid                     = window.ActiveOffice;
    ///var sid                     = 2190;
    
    var oid                     = 55;
    var sid                     = 1550;
    var cid                     = 302914;
    
    var SalesAr                 = new Array();
    SalesAr["NewSalesStart"]    = 0;
    SalesAr["NewSalesSubmit"]   = 0;
    SalesAr["EditSalesStart"]   = 0;
    SalesAr["EditSalesSubmit"]  = 0;
    
    //var accactive = $('#').accordion( "option", "active" );
    
    initSalesForm();
    
    $('h3.accHeader').live('click',function(){
        $(this).next().text('New Content');
    });
    
    function initSalesForm()
    {
        var lel = 'SalesInformation';
        
        if (SalesAr.NewSalesStart == 0)
        {
            ClearContent(lel);
        }

        initContainer(lel,'SalesHeaderContainer');
        initContainer(lel,'SalesCartContainer');
        
        initCustomerInfo('SalesHeaderContainer',null);
        initCartTabs('SalesCartContainer',null);
    }
    
    function initCartTabs(pel,cel)
    {
        //alert(pel);
        //alert(cel);
        var initText1 = '<ul></ul>';
        var initText2 = '<li><a href="#CartTab">Cart</a></li>';
        var initText3 = '<li><a href="#CatalogTab">Catalog</a></li>';
        var initText4 = '<div id="CartTab"></div>';
        var initText5 = '<div id="CatalogTab"></div>';
        
        $(initText1).appendTo('#' + pel);
        $(initText2).appendTo('#' + pel + ' ul');
        $(initText3).appendTo('#' + pel + ' ul');
        $(initText4).appendTo('#' + pel);
        $(initText5).appendTo('#' + pel);        
        
        $('#SalesCartContainer').tabs({
            selected:SalesCart_menu_tab_id,
            show:
                function(event,ui){
                var tab_id = ui.index;
                $.cookie("SalesCart_menu_tab", tab_id);
                SalesCart_menu_tab_id=tab_id;
                
                switch(tab_id)
                {                
                    case 0:
                        initCartTab('CartTab');
                    break;
                 
                    case 1:
                        initCatalogTab('CatalogTab');
                    break;
                }
            }
        });
        
        return true;
    }
    
    function initContainer(pel,cel)
    {
        var c ='<div id="' + cel + '"></div>';
        $(c).appendTo('#' + pel);

        return true;
    }
    
    function initCustomerInfo(pel,cel)
    {
        initContainer(pel,'CustomerData');        
        displayCustomerInfo('CustomerData',null,oid,sid,cid)
        
        return true;
    }
    
    function initCatalogTab(pel)
    {
        if ($('#CategoryAccordion').length == 0)
        {
            displayCategory(pel,null,oid);
        }
        return true;
    }
    
    function initCartTab(pel)
    {
        
        return true;
    }
    
    function ClearContent(el)
    {
        $(el).html('');
        return true;
    }
    
    function displayCustomerInfo(pel,cel,oid,sid,cid)
    {
        var lurl = 'http://jms.bhnmi.com/subs/ajax_sales_req.php?oid=' + oid + '&sid=' + sid + '&cid=' + cid + '&qt=get_CustomerInfo';        
        //alert(lurl);
        
        $.getJSON(lurl,function(json){
            
            if (json.count==1)
            {
                var fstartid = 'fsCustomerData';
                var istart = '<fieldset class="ui-widget ui-helper-clearfix" id="' + fstartid + '"></fieldset>';
                
                $(istart).appendTo('#' + pel);
                
                $('<legend>Customer Information</legend>').appendTo('#' + fstartid);
                $('<label class="cinfo_label">Customer ID</label>').appendTo('#' + fstartid);
                $('<div class="cinfo_data">' + json.result.cid + '</div>').appendTo('#' + fstartid);
                //$('</br>').appendTo('#' + fstartid);
                $('<label class="cinfo_label">Customer Name</label>').appendTo('#' + fstartid);
                $('<div class="cinfo_data">' + json.result.cfname + ' ' + json.result.clname + ' </div>').appendTo('#' + fstartid);
                //$('</br>').appendTo('#' + fstartid);
                $('<label class="cinfo_label">Digs</label>').appendTo('#' + fstartid);
                $('<div class="cinfo_data">' + json.result.digs + ' </div>').appendTo('#' + fstartid);
                //$('</br>').appendTo('#' + fstartid);
                
                if (json.result.sid!=0)
                {
                    $('<label class="cinfo_label">Sales Rep</label>').appendTo('#' + fstartid);
                    $('<div class="cinfo_data">' + json.result.srfname + ' ' + json.result.srlname + ' </div>').appendTo('#' + fstartid);
                }
                
                if (json.result.sidm!=0)
                {
                    $('<label class="cinfo_label">Sales Man</label>').appendTo('#' + fstartid);
                    $('<div class="cinfo_data">' + json.result.smfname + ' ' + json.result.smlname + ' </div>').appendTo('#' + fstartid);
                }               
            }
            else
            {
                alert('Error: Customer Record not found')
            }
        });
        
        return true;
    }
    
    function displayCategory(pel,cel,oid)
    {
        var lurl = 'http://jms.bhnmi.com/subs/ajax_sales_req.php?oid=' + oid + '&qt=get_Category';
        
        $.getJSON(lurl,function(json){
            
            if (json.count > 0)
            {
                $('#CatalogTab').append('<div id="CategoryAccordion"></div>');
                
                $.each(json.result,function(i,catinfo){
                    $('#CategoryAccordion').append('<h3 class="accHeader" id="' + catinfo.catid + '"><a href="#" class="loadAccDiv" id="' + catinfo.catid + '">' + catinfo.cname + '</a></h3>');
                    $('#CategoryAccordion').append('<div class="accContent" id="' + catinfo.catid + '">Some Content (' + i + ')</div>');
                });
                
                $('#CategoryAccordion').accordion({
                    autoHeight: false,
                    navigation: true
                });
            }
            else
            {
                alert('Error: No Catalog Data found')
            }
        });
        
        return true;
    }
    
    function displayCatalog(pel,cel,oid,catid)
    {
        var lurl = 'http://jms.bhnmi.com/subs/ajax_sales_req.php?oid=' + oid + '&catid=' + catid + '&qt=get_Catalog';
        //alert(lurl);
        
        $.getJSON(lurl,function(json){
            
            if (json.count > 0)
            {
                var fstartid = 'subCatalogData';
                var istart = '<div class="ui-widget ui-helper-clearfix" id="' + fstartid + '"></div>';
                
                $(istart).appendTo('#' + pel);
                
                $.each(json.result,function(i,catinfo){
                    $('<div>' + catinfo.iid + '</div><div>---------</div>').appendTo('#' + fstartid);
                });
                
                //$('#subCatalogData').text(fstartid);
            }
            else
            {
                alert('Error: No Catalog Data found')
            }
        });
        
        return true;
    }
    
    function checkLength( o, n, min ) {
        if (o.val().length < min ) {
            o.addClass( "ui-state-error" );
            //updateTips( "Length of " + n + " must be greater than " + max + "." );
            return false;
        } else {
            return true;
        }
    }
});