$('#frmLogin').validate({
    rules:
    {
        email: { required: true, email: true },
        password: { required: true }
    },
    messages:
    {
        email: { required: GetMessage('Validate', 'Email'), email: GetMessage('Login', 'ValidEmail') },
        password: { required: GetMessage('Login', 'Password') }
    },
    invalidHandler: function (form, validator) {
        $("#frmLogin .frmerror").html('');
        var errors = validator.numberOfInvalids();
        if (errors) {
            for (var i = 0; i < errors; i++) {
                var message = validator.errorList[i].message;
                var id = $(validator.errorList[i].element).attr('name');
                $("#frmLogin #error_" + id).html(message);
                $("#frmLogin #error_" + id).show();
            }
        }
        else {
            $("#frmLogin .frmerror").html('');
        }
    },
    errorPlacement: function (error, element) {
        // Override error placement to not show error messages beside elements //
    },
    submitHandler: function (form) {
        var email = document.getElementById("email").value;
        var password = document.getElementById("password").value;
        var check_value = document.getElementById("check_value").value;

        var str = "email=" + email + "&password=" + password + "&isAction=" + 'wish_login' + '&check_value='+check_value;

        $.ajax({
            type: "POST",
            url: site_url + "login_pdetail_page",
            data: str,
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(data){
                if (data.message == true) {
                    location.reload();
                } else {
                    $("#cover-spin").hide();
                    $("#myModalPopUpLogin").html(data);
                    $('#myModalPopUpLogin').modal('show');
                }
            }, 
            error:function(err){
                $('#error_email').text('');
                $('#error_password').text('');

                $('#error_email').text(err.responseJSON.errors.email);
                $('#error_password').text(err.responseJSON.errors.password);
                
                $("#error_email").show();
                $("#error_password").show();
            }
        });
    }
});