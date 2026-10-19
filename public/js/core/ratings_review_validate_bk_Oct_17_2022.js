$('#frmRatingsReview').validate({
    rules:
    {
        FirstName: { required: true },
        user_review: { required: true }
    },
    messages:
    {
        FirstName: { required: GetMessage('Validate', 'FirstName') },
        user_review: { required: GetMessage('Validate', 'Review') }
    },
    invalidHandler: function (form, validator) {
        $("#frmRatingsReview .frmerror").html('');
        var errors = validator.numberOfInvalids();
        console.log('errorserrorserrors',errors);
        if (errors) {
            for (var i = 0; i < errors; i++) {
                var message = validator.errorList[i].message;
                var id = $(validator.errorList[i].element).attr('name');
                $("#frmRatingsReview #error_" + id).html(message);
                $("#frmRatingsReview #error_" + id).show();
            }
        }
        else {
            $("#frmRatingsReview .frmerror").html('');
        }
    },
    errorPlacement: function (error, element) {
        // Override error placement to not show error messages beside elements //
        $('#error_captcha_js').hide();
        if (grecaptcha.getResponse()=="") {
            $('#error_captcha_js').html("Please select I'm not a robot.");
            $('#error_captcha_js').show();
            return false;
        }	
    },
    submitHandler: function (form) {
        var data = $("#frmRatingsReview").serialize();
        $("#cover-spin").show();
        $.ajax({
            type: "POST",
            url: site_url + "ratings_review",
            data: data,
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(msg){
                $("#cover-spin").hide();
                $("#ProductRatingsReviewPopup").html(msg);
                $('#ProductRatingsReviewPopup').modal('show');
            }, 
            error:function(err){
                $('#error_FirstName').text('');
                $('#error_user_review').text('');

                $('#error_FirstName').text(err.responseJSON.errors.FirstName);
                $('#error_user_review').text(err.responseJSON.errors.user_review);
                
                $("#error_FirstName").show();
                $("#error_user_review").show();
            }
        });
    }
});

$('#country').on('change', function (e) {
    var selectedCountry = $(this).val();
    if (selectedCountry == 'US') {
        $("#divotherstate").hide();
        $("#divstate").show();
    } else {
        $("#other_state").val('');
        $("#divotherstate").show();
        $("#divstate").hide();
    }
});