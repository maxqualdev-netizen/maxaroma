$('#frmLogin').validate({
    rules:
    {
        email: { required: true, email: true },
        password: { required: true }
    },
    messages:
    {
        email: { required: GetMessage('Login', 'Email'), email: GetMessage('Login', 'ValidEmail') },
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
		$('#error_captcha_js').hide();
		if (grecaptcha.getResponse()=="") {
			$('#error_captcha_js').html("Please select I'm not a robot.");
			$('#error_captcha_js').show();
		    return false;
		}
    },
    submitHandler: function (form) {
        var email = document.getElementById("email").value;
        var password = document.getElementById("password").value;
        var check_value = document.getElementById("check_value").value;

		$('#error_captcha_js').hide();
		if (grecaptcha.getResponse()=="") {
			$('#error_captcha_js').html("Please select I'm not a robot.");
			$('#error_captcha_js').show();
		    return false;
		}

        var str = "email=" + email + "&password=" + encodeURIComponent(password) + "&isAction=" + 'wish_login' + '&check_value='+check_value+'&g-recaptcha-response='+grecaptcha.getResponse();

        $.ajax({
            type: "POST",
            url: site_url + "wishlist_add",
            data: str,
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(msg){
                $("#cover-spin").hide();
                $("#myModalPopUpLogin").html(msg);
                $('#myModalPopUpLogin').modal('show');
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

$('#frmForgotPwd').validate({
    rules:
    {
        email: { required: true, email: true }
    },
    messages:
    {
        email: { required: GetMessage('Validate', 'Email'), email: GetMessage('Login', 'ValidEmail') }
    },
    invalidHandler: function (form, validator) {
        $("#frmForgotPwd .frmerror").html('');
        var errors = validator.numberOfInvalids();
        if (errors) {
            for (var i = 0; i < errors; i++) {
                var message = validator.errorList[i].message;
                var id = $(validator.errorList[i].element).attr('name');
                $("#frmForgotPwd #error_" + id).html(message);
                $("#frmForgotPwd #error_" + id).show();
            }
        }
        else {
            $("#frmForgotPwd .frmerror").html('');
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
        var email = document.getElementById("email").value;
        $('#error_captcha_js').hide();
		if (grecaptcha.getResponse()=="") {
			$('#error_captcha_js').html("Please select I'm not a robot.");
			$('#error_captcha_js').show();
		    return false;
		}

        var str = "email=" + email + "&isAction=" + 'wish_forget';

        $.ajax({
            type: "POST",
            url: site_url + "wishlist_add",
            data: str,
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(msg){
                grecaptcha.reset();
                $("#cover-spin").hide();
                $('#signInSignUp').modal('hide');
                $("#myModalPopUpLogin").html(msg);
                $('#myModalPopUpLogin').modal('show');
            },
            error:function(err){
                $('#error_email').text('');

                $('#error_email').text(err.responseJSON.errors.email);

                $("#error_email").show();
            }
        });
    }
});

$('#frmproduct').validate({
    rules:
    {
        description: { required: true },
        wishlist_category_id: { required: true }
    },
    messages:
    {
        description: { required: GetMessage('WishList', 'AddDescription') },
        wishlist_category_id: { required: GetMessage('WishList', 'Category') }
    },
    invalidHandler: function (form, validator) {
        $("#frmproduct .frmerror").html('');
        var errors = validator.numberOfInvalids();
        if (errors) {
            for (var i = 0; i < errors; i++) {
                var message = validator.errorList[i].message;
                var id = $(validator.errorList[i].element).attr('name');
                $("#frmproduct #error_" + id).html(message);
                $("#frmproduct #error_" + id).show();
            }
        }
        else {
            $("#frmproduct .frmerror").html('');
        }
    },
    errorPlacement: function (error, element) {
        // Override error placement to not show error messages beside elements //
    },
    submitHandler: function (form) {
        var data = $("#frmproduct").serialize();
        $("#cover-spin").hide();
        $.ajax({
            type: "POST",
            url: site_url + "wishlist_add",
            data: data,
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(msg){
                $("#cover-spin").hide();
                $("#myModalPopUpLogin").html(msg);
                $('#myModalPopUpLogin').modal('show');
            },
            error:function(err){
                $('#error_description').text('');
                $('#error_wishlist_category_id').text('');

                $('#error_description').text(err.responseJSON.errors.description);
                $('#error_wishlist_category_id').text(err.responseJSON.errors.wishlist_category_id);

                $("#error_description").show();
                $("#error_wishlist_category_id").show();
            }
        });
    }
});

$('#frmcategory').validate({
    rules:
    {
        // category_name: { required: true },
        // description: { required: true }
    },
    messages:
    {
        // description: { required: GetMessage('WishList', 'AddDescription') },
        // category_name: { required: GetMessage('WishList', 'AddCategory') }
    },
    invalidHandler: function (form, validator) {
        $("#frmcategory .frmerror").html('');
        var errors = validator.numberOfInvalids();
        if (errors) {
            for (var i = 0; i < errors; i++) {
                var message = validator.errorList[i].message;
                var id = $(validator.errorList[i].element).attr('name');
                $("#frmcategory #error_" + id).html(message);
                $("#frmcategory #error_" + id).show();
            }
        }
        else {
            $("#frmcategory .frmerror").html('');
        }
    },
    errorPlacement: function (error, element) {
        // Override error placement to not show error messages beside elements //
    },
    submitHandler: function (form) {
        var data = $("#frmcategory").serialize();
        $("#cover-spin").hide();
        $.ajax({
            type: "POST",
            url: site_url + "wishlist_add",
            data: data,
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(msg){
                $("#cover-spin").hide();
                $("#myModalPopUpLogin").html(msg);
                $('#myModalPopUpLogin').modal('show');
            },
            error:function(err){
                $('#error_category_name').text('');
                $('#error_description').text('');

                $('#error_category_name').text(err.responseJSON.errors.category_name);
                $('#error_description').text(err.responseJSON.errors.description);

                $("#error_category_name").show();
                $("#error_description").show();
            }
        });
    }
});