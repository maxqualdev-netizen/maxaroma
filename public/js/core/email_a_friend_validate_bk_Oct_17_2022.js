$('#frmFriend').validate({
    rules:
    {
        fmail1: { required: true, email: true },
        youremail: { required: true, email: true },
        fmail2: { email: 
                    function(){
                        return $("#fmail2").val() !== ""
                    }
                },
        fmail3: { email: 
                    function(){
                        return $("#fmail3").val() !== ""
                    }
                },
        fmail4: { email: 
                    function(){
                        return $("#fmail4").val() !== ""
                    }
                },
        fmail5: { email: 
                    function(){
                        return $("#fmail5").val() !== ""
                    }
                },
        // keycode: {
        //     required: function () {
        //         if (grecaptcha.getResponse() == '') {
        //             return true;
        //         } else {
        //             return false;
        //         }
        //     }
        // },
    },
    messages:
    {
        fmail1: { required: GetMessage('Validate', 'Email'), email: GetMessage('Login', 'ValidEmail') },
        youremail: { required: GetMessage('Validate', 'Email'), email: GetMessage('Login', 'ValidEmail') },
        fmail2: { email: GetMessage('Login', 'ValidEmail') },
        fmail3: { email: GetMessage('Login', 'ValidEmail') },
        fmail4: { email: GetMessage('Login', 'ValidEmail') },
        fmail5: { email: GetMessage('Login', 'ValidEmail') },
        // keycode:{ required: "Please select I am not a robot." },
    },
    invalidHandler: function (form, validator) {
        $("#frmFriend .frmerror").html('');
        var errors = validator.numberOfInvalids();
        if (errors) {
            for (var i = 0; i < errors; i++) {
                var message = validator.errorList[i].message;
                var id = $(validator.errorList[i].element).attr('name');
                $("#frmFriend #error_" + id).html(message);
                $("#frmFriend #error_" + id).show();
            }
        }
        else {
            $("#frmFriend .frmerror").html('');
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
        var data = $("#frmFriend").serialize();
        $("#cover-spin").show();
        $.ajax({
            type: "POST",
            url: site_url + "email_friend",
            data: data,
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(msg){
                $("#cover-spin").hide();
                $("#EmailFriendPopup").html(msg);
                $('#EmailFriendPopup').modal('show');
            }, 
            error:function(err){
                console.log('cError : '+ err);
                $('#error_fmail1').text('');
                $('#error_youremail').text('');
                $('#error_fmail2').text('');
                $('#error_fmail3').text('');
                $('#error_fmail4').text('');
                $('#error_fmail5').text('');
                $('#error_captcha_js').text('');

                $('#error_fmail1').text(err.responseJSON.errors.fmail1);
                $('#error_youremail').text(err.responseJSON.errors.youremail);
                $('#error_fmail2').text(err.responseJSON.errors.fmail2);
                $('#error_fmail3').text(err.responseJSON.errors.fmail3);
                $('#error_fmail4').text(err.responseJSON.errors.fmail4);
                $('#error_fmail5').text(err.responseJSON.errors.fmail5);
                $('#error_captcha_js').text(err.responseJSON.errors.g-recaptcha-response);
                
                $("#error_fmail1").show();
                $("#error_youremail").show();
                $("#error_fmail2").show();
                $("#error_fmail3").show();
                $("#error_fmail4").show();
                $("#error_fmail5").show();
                $("#error_captcha_js").show();
            }
        });
    }
});