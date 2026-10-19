jQuery.validator.addMethod('IsEmailExist', function (value, element){
    var isSuccess = false;
    var token = $('meta[name="csrf-token"]').attr('content');
    var email = value;
    $.ajax({
        type:'POST',
        url:site_url+'checkmember',
        headers: {
            'X-CSRF-TOKEN': token
        },
        datatype: 'JSON',
        data:{
            action : 'chkmember',
            email : email
        },
        async: false, 
        success:function(data) {
            if(data)
            {
                return isSuccess = false;
            } else {
                return isSuccess = true;
            }
        }
    });
    return isSuccess;

}, "You already have an account with this email address, please <a href='"+site_url+"login.html'>login</a> or change your email address");

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;    
    if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 43 && charCode != 45 && charCode != 40 && charCode != 41  && charCode != 32) {
        return false;
    }
    return true;
}

$('#formSignIn').validate({
    rules:
    {
        email: { required: true, 
                 email: true
                },
        rememberme: {
                 required: true
        },
        password: { required: true }
    },
    messages:
    {
        email: { required: GetMessage('Validate', 'Email'), email: GetMessage('Login', 'ValidEmail') },
        password: { required: GetMessage('Login', 'Password') },
        rememberme: { required: 'please select remember me' }
    },
    invalidHandler: function (form, validator) {
        $("#formSignIn .frmerror").html('');
        var errors = validator.numberOfInvalids();
        if (errors) {
            for (var i = 0; i < errors; i++) {
                var message = validator.errorList[i].message;
                var id = $(validator.errorList[i].element).attr('name');
                $("#formSignIn #error_" + id).html(message);
                $("#formSignIn #error_" + id).show();
            }
        }
        else {
            $("#formSignIn .frmerror").html('');
        }
    },
    errorPlacement: function (error, element) {
        // Override error placement to not show error messages beside elements //
		var captchaCount = $('body').find('div.g-recaptcha').length;  
        var new_index = captchaCount - 1; 
		$('#error_captcha_js').hide();
		//if (grecaptcha.getResponse()=="") {
		if (grecaptcha.getResponse(new_index)=="") {	
			$('#error_captcha_js').html("Please select I'm not a robot.");
			$('#error_captcha_js').show();
		    return false;
		}
    },
    submitHandler: function (form) {
        var email = document.getElementById("email").value;
        var password = document.getElementById("password").value;
        var check_value = document.getElementById("check_value").value;
		var checkloginPopval = $("#checkloginPopval").val();
		var captchaCount = $('body').find('div.g-recaptcha').length;  
        var new_index = captchaCount - 1;
		$('#error_captcha_js').hide();
		//if (grecaptcha.getResponse()=="") {
		if (grecaptcha.getResponse(new_index)=="") {	
			$('#error_captcha_js').html("Please select I'm not a robot.");
			$('#error_captcha_js').show();
		    return false;
		}
		
        //var str = "email=" + email + "&password=" + password + "&isAction=" + 'sign_in' + '&check_value=' + check_value +'&g-recaptcha-response='+grecaptcha.getResponse();	
		var str = "email=" + email + "&password=" + password + "&isAction=" + 'sign_in' + '&check_value=' + check_value +'&g-recaptcha-response='+grecaptcha.getResponse(new_index);	

        $.ajax({
            type: "POST",
            //url: site_url + "signin_signup",
			url: site_url + "signin_signup_new",
            data: str,
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if (data.message == true) {
                    if(checkloginPopval=="1")
					{
					window.location = site_url + "checkout";
					}
					else
					{
                    window.location = site_url + "myaccount.html";
					}
                } else {
                    $("#cover-spin").hide();
                    $("#signInSignUp").html(data);
                    $('#signInSignUp').modal('show');
                }
            },
            error: function (err) {
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

jQuery.validator.addMethod("noSpace", function(value, element) { 
	return value.indexOf(" ") < 0 && value != ""; 
	}, "No space please and don't leave it empty");

$('#frmSignUpFirstPart').validate({
    rules:
    {
        email: { required: true,
                 email: true
                },
        password: { required: true,
                    noSpace: true,
                    minlength: 6
                 },
        termsprivacy: { required: true }
    },
    messages:
    {
        email: { required: GetMessage('Validate', 'Email'), email: GetMessage('Login', 'ValidEmail') },
        password: { required: GetMessage('Login', 'Password') },
        termsprivacy: { required: 'Please checkmark Terms and Privacy Policy.' }
    },
    invalidHandler: function (form, validator) {
        $("#frmSignUpFirstPart .frmerror").html('');
        var errors = validator.numberOfInvalids();
        if (errors) {
            for (var i = 0; i < errors; i++) {
                var message = validator.errorList[i].message;
                var id = $(validator.errorList[i].element).attr('name');
                $("#frmSignUpFirstPart #error_" + id).html(message);
                $("#frmSignUpFirstPart #error_" + id).show();
            }
        }
        else {
            $("#frmSignUpFirstPart .frmerror").html('');
        }
    },
    errorPlacement: function (error, element) {
        // Override error placement to not show error messages beside elements //
		$('#error_captcha_js').hide();
		var captchaCount = $('body').find('div.g-recaptcha').length;  
        var new_index = captchaCount - 1;
		//if (grecaptcha.getResponse()=="") {
		if (grecaptcha.getResponse(new_index)=="") { 	
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
		var captchaCount = $('body').find('div.g-recaptcha').length;  
        var new_index = captchaCount - 1;
		//if (grecaptcha.getResponse()=="") {
		if (grecaptcha.getResponse(new_index)=="") {	
			$('#error_captcha_js').html("Please select I'm not a robot.");
			$('#error_captcha_js').show();
		    return false;
		}

        //var str = "email=" + email + "&password=" + password + "&isAction=" + 'sign_upsecondpart' + '&check_value=' + check_value +'&g-recaptcha-response='+grecaptcha.getResponse();	
		var str = "email=" + email + "&password=" + password + "&isAction=" + 'sign_upsecondpart' + '&check_value=' + check_value +'&g-recaptcha-response='+grecaptcha.getResponse(new_index);		

        $.ajax({
            type: "POST",
            //url: site_url + "signin_signup",
			url: site_url + "signin_signup_new",
            data: str,
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if (data.message == true) {
                    $("#cover-spin").hide();
                    $("#signInSignUp").html(data);
                    $('#signInSignUp').modal('show');
                } else {
                    $("#cover-spin").hide();
                    $("#signInSignUp").html(data);
                    $('#signInSignUp').modal('show');
                }
            },
            error: function (err) {
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

$('#frmSignUpSecondPart').validate({
    rules:
    {
        first_name: { required: true },
        last_name: { required: true },
        address1: { required: true },
        city: { required: true },
        termsprivacy: { required: true },
        agreepolicy: { required: true },
        zip: { required: true },
        email: { required: true, 
                 email: true,
                 IsEmailExist: true 
                },
        password: { required: true,
                    minlength: 6,
                    noSpace:true
                  },
        confirm_Password: { required: true, equalTo: '#password' },
        phone: {
            required: true,
            minlength: 10,
            phoneUS: true
        },
        instagram: {
            required:
                function () {
                    return $("#hear_about").val() == "Instagram"
                }
        },
        youtube: {
            required:
                function () {
                    return $("#hear_about").val() == "Youtube"
                }
        },
        other: {
            required:
                function () {
                    return $("#hear_about").val() == "Other"
                }
        },
        state: {
            required:
                function () {
                    return $("#country").val() == "US"
                }
        },
        other_state: {
            required:
                function () {
                    return $("#country").val() != "US"
                }
        }
    },
    messages:
    {
        first_name: { required: GetMessage('Validate', 'FirstName') },
        last_name: { required: GetMessage('Validate', 'LastName') },
        address1: { required: GetMessage('Validate', 'Address') },
        city: { required: GetMessage('Validate', 'City') },
        zip: { required: GetMessage('Validate', 'ZipCode') },
        phone: { required: GetMessage('Validate', 'Phone') },
        email: { required: GetMessage('Validate', 'ValidEmail'), email: GetMessage('Validate', 'ValidEmail') },
        password: { required: GetMessage('Register', 'Password') },
        confirm_Password: { required: GetMessage('Validate', 'ValidConfirmPassword'), equalTo: GetMessage('Validate', 'ValidConfirmPassword') },
        instagram: { required: GetMessage('Register', 'Instagram') },
        youtube: { required: GetMessage('Register', 'Youtube') },
        other: { required: GetMessage('Register', 'OtherText') },
        state: { required: GetMessage('Validate', 'State') },
        other_state: { required: GetMessage('Validate', 'OtherState') },
        termsprivacy: { required: 'Please checkmark Terms and Privacy Policy.' },
        agreepolicy: { required: 'Please checkmark Agree Policy.' }
    },
    invalidHandler: function (form, validator) {
        $("#frmSignUpSecondPart .frmerror").html('');
        var errors = validator.numberOfInvalids();
        if (errors) {
            for (var i = 0; i < errors; i++) {
                var message = validator.errorList[i].message;
                var id = $(validator.errorList[i].element).attr('name');
                $("#frmSignUpSecondPart #error_" + id).html(message);
                $("#frmSignUpSecondPart #error_" + id).show();
            }
        }
        else {
            $("#frmSignUpSecondPart .frmerror").html('');
        }
    },
    errorPlacement: function (error, element) {
        // Override error placement to not show error messages beside elements //
        $('#error_captcha_js').hide();
		var captchaCount = $('body').find('div.g-recaptcha').length;  
        var new_index = captchaCount;
        //if (grecaptcha.getResponse()=="") {
		if (grecaptcha.getResponse(new_index)=="") {	
            $('#error_captcha_js').html("Please select I'm not a robot.");
            $('#error_captcha_js').show();
            return false;
        }
        
    },
    submitHandler: function (form) {
        var str = $("#frmSignUpSecondPart").serialize();
		
		$('#error_captcha_js').hide();
		var captchaCount = $('body').find('div.g-recaptcha').length;  
        var new_index = captchaCount;
        //if (grecaptcha.getResponse()=="") {
		if (grecaptcha.getResponse(new_index)=="") {	
            $('#error_captcha_js').html("Please select I'm not a robot.");
            $('#error_captcha_js').show();
            return false;
        }
		
        $.ajax({
            type: "POST",
            //url: site_url + "signin_signup",
			url: site_url + "signin_signup_new",
            data: str,
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if (data.message == true) {
                    window.location.href = site_url + 'myaccount.html';
                } else {
                    $("#cover-spin").hide();
                    $("#signInSignUp").html(data);
                    $('#signInSignUp').modal('show');
                }
            },
            error: function (err) {
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
            //url: site_url + "wishlist_add",
            url: site_url + "signin_signup_new",
            data: str,
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(msg){
                grecaptcha.reset();
                $("#cover-spin").hide();
                //$('#signInSignUp').modal('hide');				
                $("#signInSignUp").html(msg);
                $('#signInSignUp').modal('show');
            }, 
            error:function(err){
                $('#error_email').text('');
                $('#error_email').text(err.responseJSON.errors.email);                
                $("#error_email").show();
            }
        });
    }
});

$(document).on("click", '.gendermalefemale', function () {
    $(".nichedesignerspray").css("display","block");
});
function ShowForgetPasswordSigninPopup() {
    var str = "isAction=wish_forget" + "&isPopup=" + "Yes";
    $.ajax({
        type: "POST",
        //url: site_url + "wishlist_add",
        url: site_url + "signin_signup_new",
        data: str,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $("#signInSignUp").html('');
            $("#signInSignUp").html(response);
        }
    })
} 
