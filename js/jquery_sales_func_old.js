$(document).ready(function()
{
    var SalesInfo_menu_tab_id   = parseInt($.cookie("SalesInfo_menu_tab")) || 0;
    var ajxscript               = 'subs/ajax_sales_req.php';
    var retrIMG                 = '<img src="images/mozilla_blu.gif"> Retrieving...';
    var procIMG                 = '<img src="images/mozilla_blu.gif"> Processing...';
    var spinner                 = '<img src="images/mozilla_blu.gif">';
    
    //var oid                     = window.ActiveOffice;
    ///var sid                     = 2190;
    
    var oid                     = 55;
    var sid                     = 1550;
    var cid                     = 0;
    
    var SalesAr                 = new Array();
    SalesAr["NewSalesStart"]    = 0;
    SalesAr["NewSalesSubmit"]   = 0;
    SalesAr["EditSalesStart"]   = 0;
    SalesAr["EditSalesSubmit"]  = 0;
    
    //initSalesTabs();
    
    initSalesForm();
    
    function initSalesTabs()
    {
        var initText1 = '<ul></ul>';
        var initText2 = '<li><a href="#WorkingTab">Working</a></li>';
        var initText3 = '<li><a href="#ReviewTab">Review</a></li>';
        var initText4 = '<li><a href="#CompleteTab">Complete</a></li>';
        var initText5 = '<li><a href="#SalesFormTab">New</a></li>';
        var initText6 = '<div id="WorkingTab"></div>';
        var initText7 = '<div id="ReviewTab"></div>';
        var initText8 = '<div id="CompleteTab"></div>';
        var initText9 = '<div id="SalesFormTab"></div>';
        
        $(initText1).appendTo('#SalesInformation');
        $(initText2).appendTo('#SalesInformation ul');
        $(initText3).appendTo('#SalesInformation ul');
        $(initText4).appendTo('#SalesInformation ul');
        $(initText5).appendTo('#SalesInformation ul');
        $(initText6).appendTo('#SalesInformation');
        $(initText7).appendTo('#SalesInformation');
        $(initText8).appendTo('#SalesInformation');
        $(initText9).appendTo('#SalesInformation');
        
        $('#SalesInformation').tabs({
            selected:SalesInfo_menu_tab_id,
            show:
                function(event,ui){
                var tab_id = ui.index;
                $.cookie("SalesInfo_menu_tab", tab_id);
                SalesInfo_menu_tab_id=tab_id;
                
                switch(tab_id)
                {                
                    case 0:
                        load_WorkingTab();
                    break;
                 
                    case 1:
                        load_ReviewTab();
                    break;
                    
                    case 2:
                        load_CompletedTab();
                    break;
                
                    case 3:
                        load_NewTab();
                    break;
                }
            }
        });
        
        return true;
    }
    
    function initSalesForm()
    {
        var lel = '#SalesFormContainer';
        
        if (SalesAr.NewSalesStart == 0)
        {
            ClearContent(lel);
        }
        
        SalesForm(lel);
    }
    
    function ClearContent(el)
    {
        $(el).html('');
        return true;
    }
    
    function load_WorkingTab()
    {
        var lel = '#WorkingTab';
        ClearContent(lel);
        //displayCustomerList(oid,sid,SalesInfo_menu_tab_id,lel)
        //CustomerSearchForm(lel);
        return true;
    }
    
    function load_ReviewTab()
    {
        var lel = '#ReviewTab';
        ClearContent(lel);
        
        $('<button id="openCustomerSearch">Customer Search</button>').appendTo(lel);
        
        $('#openCustomerSearch')
            .button()
            .click(function() {
                //$('#CustomerSearchForm').dialog("open");
                CustomerSearchForm(lel);
            });
        //displayCustomerList(oid,sid,SalesInfo_menu_tab_id,lel)
        return true;
    }
    
    function load_CompletedTab()
    {
        var lel = '#CompleteTab';
        ClearContent(lel);
        //displayCustomerList(oid,sid,SalesInfo_menu_tab_id,lel)
        return true;
    }
    
    function load_NewTab()
    {
        var lel = '#SalesFormTab';
        
        if (SalesAr.NewSalesStart == 0)
        {
            ClearContent(lel);
        }
        
        SalesForm(lel);
        
        return true;
    }
    
    function SalesForm(el)
    {
        var SalesFormContainer='<div id="SalesForm"></div>';
        var CustomerList='<div id="CustomerList">CustList</div>';
        var SalesFormSelects='<div id="SalesSelects">Selects</div>';
        var SalesFormHeader='<div id="SalesHeader">Header</div>';
        var SalesFormCart='<div id="SalesCart">Cart</div>';
        
        $(SalesFormContainer).appendTo(el);
        $(CustomerList).appendTo('#SalesForm');
        $(SalesFormSelects).appendTo('#SalesForm');
        $(SalesFormHeader).appendTo('#SalesForm');
        $(SalesFormCart).appendTo('#SalesForm');
        
        return true;
    }
    
    function CustomerSearchForm(el)
    {
        var CustomerSearchFormContainer='<div id="CustomerSearchForm">Last Name <input type="text" id="in_clName" class="text ui-widget-content ui-corner-all"><br>Results:<br><div id="CSResult"></div></div>';
        $(CustomerSearchFormContainer).dialog({
            modal: true,
            title: 'Customer Search',
            buttons:{
                Close: function(){
                    $(this).dialog('destroy');
                    $('#CustomerSearchForm').remove();
                },
                Search: function(){
                    ClearContent('#CSResult');
                    
                    //checkLength( '#in_clName', n, min );
                    
                    displayCustomerList(oid,sid,'#CSResult');
                }
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

    
    function displayCustomerList(oid,sid,el)
    {
        //alert(cid);
        ajx_CustomerListDivs(oid,sid,el);
        return true;
    }
    
    function ajx_CustomerListDivs(oid,sid,el)
    {
        var lurl = 'http://jms.bhnmi.com/subs/ajax_sales_req.php?oid=' + oid + '&sid=' + sid + '&qt=get_CustomerList&stext=' + $('#in_clName').val();
        
        //alert(lurl);
        
        $.getJSON(lurl,function(json){
            $.each(json.result,function(i,cinfo){
                $(el).appendTo('<div class="cinfo_data"><div class="cid">' + cinfo.cid + '</div><div class="cfname">' + cinfo.cfname + '</div><div class="clname">' + cinfo.clname + '</div><div class="sid">' + cinfo.sid + '</div><div class="srfname">' + cinfo.srfname + '</div><div class="srlname">' + cinfo.srlname + '</div><div class="sidm">' + cinfo.sidm + '</div><div class="smfname">' + cinfo.smfname + '</div><div class="smlname">' + cinfo.smlname + '</div><div class="digdate">' + cinfo.digdate + '</div><div>---------</div></div>');
            });
        });
        
        return true;
    }
});