$(document).ready(function(){
	$("#hear_about").change(function(){
		$(".here_about_opt").hide();
		if($(this).val() == 'Instagram')
			$("#divinstagram").show();
		if($(this).val() == 'Youtube')
			$("#divyoutube").show();
		if($(this).val() == 'Other')
			$("#divother").show();
	});

	$("#country").change(function(){
		if($(this).val() != 'US') {
			$("#divstate").hide();
			$("#divotherstate").show();
		} else {
			$("#divotherstate").hide();
			$("#divstate").show();
		}
	});
});

$(document).ready(function () {
    const nonEnglishMessage =
        "Please enter the first name using English characters only.";

    $("#first_name").on("input", function () {
        const enteredValue = $(this).val();

        // Allow English letters, spaces, apostrophes and hyphens
        const cleanedValue = enteredValue.replace(/[^A-Za-z\s'-]/g, "");

        if (enteredValue !== cleanedValue) {
            $(this).val(cleanedValue);

            $("#error_first_name")
                .text(nonEnglishMessage)
                .show();

            $(this).addClass("input-error");
        } else {
            // Do not remove an error added by another validation
            if ($("#error_first_name").text().trim() === nonEnglishMessage) {
                $("#error_first_name").text("").hide();
                $(this).removeClass("input-error");
            }
        }
    });
});

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;    
    if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 43 && charCode != 45 && charCode != 40 && charCode != 41  && charCode != 32) {
        return false;
    }
    return true;
}

$('#frmRegister input').keypress(function (e) 
{
	if (e.which == 13) 
	{
		submitform('frmRegister');
		return false;
	}
});

let file = "public/css/core/datepicker.css";
$('head').append('<link rel="stylesheet" href="'+ file +'" type="text/css" />');
$("#birthday").attr("data-date", "mm/dd/yyyy");
$("#birthday").on("change", function() {
	let file = "public/css/core/datepicker.css";
	$('head').append('<link rel="stylesheet" href="'+ file +'" type="text/css" />');
    this.setAttribute(
        "data-date",
        moment(this.value, "YYYY-MM-DD")
        .format( this.getAttribute("data-date-format") )
    )
});

$(document).ready(function()
{
	$("#btnRegisterReset").click(function(){
		resetform('frmRegister');
	});

	$("#btnRegister").click(function(){
		submitform('frmRegister');
	});

	jQuery.validator.addMethod('phoneUS', function (value, element){
 
		return this.optional(element) || /^[0-9 +-]+$/.test(value);
	   
	  }, "Please enter a valid phone number");
	
	jQuery.validator.addMethod("noSpace", function(value, element) { 
	return value.indexOf(" ") < 0 && value != ""; 
	}, "No space please and don't leave it empty");

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
				{console.log('exist');
					return isSuccess = false;
				} else {
					console.log('notexist');
					return isSuccess = true;
				}
			}
		});
		return isSuccess;
	
	}, "You already have an account with this email address, please <a href='"+site_url+"login.html'>login</a> or change your email address");

	$("#frmRegister").submit(function()
	{
		$("#frmRegister .frmerror").html('');
		$('#frmRegister').validate({
			ignore: ".ignore",
			rules: 
			{
				first_name: { required: true  },
				last_name: { required: true  },
				address1: { required: true  },
				city: { required: true  },
				zip: { required: true  },
				email: 	{ required: true,
					      email: true,
						  IsEmailExist: true 
						},
				password: { required: true, 
							noSpace: true,
							minlength: 6
							},
				confirm_Password: { required: true, equalTo: '#password' },
				phone: {
					required: true,
					minlength: 10,
					phoneUS: true
				},
				instagram: { required: 
									function(){
				                        return $("#hear_about").val() == "Instagram"
				                  	}
				},
				youtube: { required: 
									function(){
				                        return $("#hear_about").val() == "Youtube"
				                  	}
				},
				other: { required: 
									function(){
				                        return $("#hear_about").val() == "Other"
				                  	}
				},
				state: { required: 
									function(){
				                        return $("#country").val() == "US"
				                  	}
				},
				other_state: { required: 
									function(){
				                        return $("#country").val() != "US"
				                  	}
				},
				hiddenRecaptcha: {
					required: function () {
						if (grecaptcha.getResponse() == '') {
							return true;
						} else {
							return false;
						}
					}
				}
			},
			messages: 
			{
				first_name: 	{ required: GetMessage('Validate','FirstName') },
				last_name: 	{ required: GetMessage('Validate','LastName') },
				address1: 	{ required: GetMessage('Validate','Address') },
				city: 	{ required: GetMessage('Validate','City') },
				zip: 	{ required: GetMessage('Validate','ZipCode') },
				phone: 	{ required: GetMessage('Validate','Phone') ,IsEmailExist:'sdadasd'},
				email: 	{ required: GetMessage('Validate','ValidEmail'), email: GetMessage('Validate','ValidEmail') },
				password: { required: GetMessage('Register','Password') },
				confirm_Password: 	{ required: GetMessage('Validate','ValidConfirmPassword'), equalTo: GetMessage('Validate','ValidConfirmPassword') },
				instagram: 	{ required: GetMessage('Register','Instagram') },
				youtube: 	{ required: GetMessage('Register','Youtube') },
				other: 	{ required: GetMessage('Register','OtherText') },
				state: 	{ required: GetMessage('Validate','State') },
				other_state: 	{ required: GetMessage('Validate','OtherState') },
				hiddenRecaptcha: 	{ required: "Please select I'm not a robot." }
			},
			onsubmit: false,
			invalidHandler: function(form, validator) 
			{
				var errors = validator.numberOfInvalids();
				if (errors) 
				{
					for(var i=0;i<errors;i++)
					{
						var message = validator.errorList[i].message;
						var id = $(validator.errorList[i].element).attr('name');
						$("#frmRegister #error_"+id).html(message);
						$("#frmRegister #error_"+id).show();
					}
				} 
			   else 
			   {
				   $("#frmRegister .frmerror").html('');
			   }
			},
			errorPlacement: function(error, element) 
			{
				if(element.attr('name') == 'hiddenRecaptcha'){
					error.appendTo($('#error_captcha_js')); 
						$('#error_captcha_js').html("Please select I'm not a robot.");
						$('#error_captcha_js').show();
				}
			}
		});
		
		if(!$('#frmRegister').valid()) {
			return false;
		}
		
    });

});
