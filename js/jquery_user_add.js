$(document).ready(function()
{
    var ajxscript = 'subs/ajax_user_req.php';
    
    $('#loginid').keyup(function() {
        if ($.trim($('#loginid').val())!='')
        {
            $('#ajxcontent').empty();
            $.ajax({
                cache:false,
                type : 'POST',
                url : ajxscript,
                dataType : 'html',
                data: {
                    call : 'user',
                    subq : 'get_systemLogIds',
                    itext: $('#loginid').val(),
                    optype : 'table'
                },
                success : function(data){
                    $('#ajxcontent').hide('blind',300);
                    $('#ajxcontent').append().html(data).show('blind',300);
                },
                error : function(XMLHttpRequest, textStatus, errorThrown) {
                    $('#ajxcontent').hide('blind',300);
                    $('#ajxcontent').html(textStatus).show('blind',300);
                }
            });
        }
    });
    
    $("#adduser").validate({
        rules: {
            fname: "required",
            lname: "required",
            login: "required",
            p1: "required",
            hdate: "required",
            email: {
                required: true,
                email: true
            }
        }
    });
});