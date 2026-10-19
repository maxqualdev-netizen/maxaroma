$(document).ready(function () {
    $("#frmPopUpNewsletter .frmerror").html('');
    $('#frmPopUpNewsletter').validate({
        rules: {
            newsletter_email: { required: true, email: true }
        },
        messages: {
            newsletter_email: { required: GetMessage('Validate', 'Email'), email: GetMessage('Login', 'ValidEmail') }
        },
        invalidHandler: function (form, validator) {
            $("#frmPopUpNewsletter .frmerror").html('');
            var errors = validator.numberOfInvalids();
            if (errors) {
                for (var i = 0; i < errors; i++) {
                    var message = validator.errorList[i].message;
                    var id = $(validator.errorList[i].element).attr('name');
                    $("#frmPopUpNewsletter #error_" + id).html(message);
                    $("#frmPopUpNewsletter #error_" + id).show();
                }
            }
            else {
                $("#frmPopUpNewsletter .frmerror").html('');
            }
        },
        errorPlacement: function (error, element) {
            // Override error placement to not show error messages beside elements //
        },
        submitHandler: function (form) {
            var obj_email = document.getElementById("newsletter_email").value;
            $("#cover-spin").show();
            $.ajax({
                type: 'POST',
                url: site_url + "instant_coupon_ajax",
                data: 'email=' + obj_email,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $("#cover-spin").show('hide');
                    $('#coupon-modal-popup').modal('hide');
                    if (data.message == "success") {
                        $('#coupon-modal-popup-msg').modal('show');
                    }
                    else {
                        $('#coupon-modal-popup-msg').modal('show');
                        $("#newsPopUpLetter").html(data.message);
                    }
                    $("#loadingimages").hide();
                },
                error: function (err) {
                    console.log(err);
                    $('#error_newsletter_email').text('');

                    $('#error_newsletter_email').text(err.responseJSON.errors.email);
                    
                    $("#error_newsletter_email").show();
                },
            });

        }
    });
});
/*
 * Instant PopUp code End
 */


