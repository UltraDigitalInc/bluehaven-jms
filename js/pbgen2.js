$(document).ready(function() {
    $('#includeAll').live('click',function(e){
        e.preventDefault();
        //alert('Bingo');
        $.each($('.pbitem'), function(){
            //alert('test');
            $(this).attr('checked',true);
        });
    });
    
    $('#exincludeAll').live('click',function(e){
        e.preventDefault();
        //alert('Bingo');
        $.each($('.pbitem'), function(){
            //alert('test');
            $(this).attr('checked',false);
        });
    });
    
    $('#btn_updateItem').live('click',function(e){
        e.preventDefault();
        var conf= confirm('Confirm your Update Request.\n\nThis action is not reversible.\n\nClick Ok to continue.');
        
        if (conf) {
            $('#updateItemsForm').submit();
        }
    });
});