$('#FormNicheFragrances').validate({
    rules:
    {
        email: { required: true, email: true },
        first_name: { required: true },
        last_name: { required: true },
        gender: { required: true }
    },
    messages:
    {
        email: { required: GetMessage('Validate', 'Email'), email: GetMessage('Login', 'ValidEmail') },
        first_name: { required: GetMessage('Validate', 'FirstName') },
        last_name: { required: GetMessage('Validate', 'LastName') },
        gender: { required: GetMessage('Validate', 'Gender') }
    },
    invalidHandler: function (form, validator) {
        $("#FormNicheFragrances .frmerror").html('');
        var errors = validator.numberOfInvalids();
        if (errors) {
            for (var i = 0; i < errors; i++) {
                var message = validator.errorList[i].message;
                var id = $(validator.errorList[i].element).attr('name');
                $("#FormNicheFragrances #error_" + id).html(message);
                $("#FormNicheFragrances #error_" + id).show();
            }
        }
        else {
            $("#FormNicheFragrances .frmerror").html('');
        }
    },
    errorPlacement: function (error, element) {
        // Override error placement to not show error messages beside elements //
    },
    submitHandler: function (form) {
        var data = $("#FormNicheFragrances").serialize();
        $("#cover-spin").hide();
        $.ajax({
            type: "POST",
            url: site_url + "niche_fragrance_membership",
            data: data,
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(msg){
                $("#cover-spin").hide();
                $("#NicheFragrancesPopup").html(msg);
                $('#NicheFragrancesPopup').modal('show');
            }, 
            error:function(err){
                $('#error_email').text('');
                $('#error_first_name').text('');
                $('#error_last_name').text('');
                $('#error_gender').text('');

                $('#error_email').text(err.responseJSON.errors.email);
                $('#error_first_name').text(err.responseJSON.errors.first_name);
                $('#error_last_name').text(err.responseJSON.errors.last_name);
                $('#error_gender').text(err.responseJSON.errors.gender);
                
                $("#error_email").show();
                $("#error_first_name").show();
                $("#error_last_name").show();
                $("#error_gender").show();
            }
        });
    }
});