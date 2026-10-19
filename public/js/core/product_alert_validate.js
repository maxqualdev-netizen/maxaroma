$('#frmproductalert').validate({
    rules:
    {
        email: { required: true, email: true }
    },
    messages:
    {
        email: { required: GetMessage('Validate', 'Email'), email: GetMessage('Login', 'ValidEmail') }
    },
    invalidHandler: function (form, validator) {
        $("#frmproductalert .frmerror").html('');
        var errors = validator.numberOfInvalids();
        if (errors) {
            for (var i = 0; i < errors; i++) {
                var message = validator.errorList[i].message;
                var id = $(validator.errorList[i].element).attr('name');
                $("#frmproductalert #error_" + id).html(message);
                $("#frmproductalert #error_" + id).show();
            }
        }
        else {
            $("#frmproductalert .frmerror").html('');
        }
    },
    errorPlacement: function (error, element) {
        // Override error placement to not show error messages beside elements //
    },
    submitHandler: function (form) {
        var data = $("#frmproductalert").serialize();
        $("#cover-spin").hide();
        $.ajax({
            type: "POST",
            url: site_url + "product_alert_me",
            data: data,
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(msg){
                $("#cover-spin").hide();
                $("#ProductAlertMePopup").html(msg);
                $('#ProductAlertMePopup').modal('show');
            }, 
            error:function(err){
                $('#error_email').text('');

                $('#error_email').text(err.responseJSON.errors.email);
                
                $("#error_email").show();
            }
        });
    }
});