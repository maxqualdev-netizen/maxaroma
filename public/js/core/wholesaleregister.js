$(document).ready(function() {
	// $("#diveinnumber").hide();
	$('#state').on('change', function (e) {
	    var selectedState = $(this).val();
		if(selectedState == 'NY') {
	    	$("#diveinnumber").show();
		} else {
	    	$("#diveinnumber").hide();
		}
	});
});


$('#frmWholesaleRegister input').keypress(function (e) 
{
	if (e.which == 13) 
	{
		submitform('frmWholesaleRegister');
		return false;
	}
});

$(document).ready(function()
{
	$("#btnWholesaleRegisterReset").click(function(){
		resetform('frmWholesaleRegister');
	});

	$("#btnWholesaleRegister").click(function(){
		submitform('frmWholesaleRegister');
	});

	jQuery.validator.addMethod('phoneUS', function (value, element){
 
		return this.optional(element) || /^[0-9 +-]+$/.test(value);
	   
	  }, "Please enter a valid phone number");

	jQuery.validator.addMethod("noSpace", function(value, element) { 
	return value.indexOf(" ") < 0 && value != ""; 
	}, "No space please and don't leave it empty");

	$("#frmWholesaleRegister").submit(function()
	{
		$("#frmWholesaleRegister .frmerror").html('');
		$('#frmWholesaleRegister').validate({
			ignore: ".ignore",
			rules: 
			{
				company_name: { required: true  },
				first_name: { required: true  },
				last_name: { required: true  },
				address1: { required: true  },
				city: { required: true  },
				country: { required: true  },
				zip: { required: true  },
				email: 	{ required: true,email: true },
				password: { required: true,
                    noSpace: true,
                    minlength: 6
                 },
				confirm_password: { required: true, equalTo: '#password', minlength: 6 },
				hear_about: { required: true  },
				phone: {
					required: true,
					minlength: 10,
					phoneUS: true
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
				einnumber: { required: 
									function(){
				                        return $("#state").val() == "NY"
				                  	}
				},
				salestax_id: { required: 
									function(){
				                        return $("#state").val() == "NY"
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
				company_name: { required: GetMessage('Register','CompanyName') },
				first_name: { required: GetMessage('Validate','FirstName') },
				last_name: { required: GetMessage('Validate','LastName') },
				address1: { required: GetMessage('Validate','Address') },
				city: { required: GetMessage('Validate','City') },
				country: { required: GetMessage('Validate','Country') },
				zip: { required: GetMessage('Validate','ZipCode') },
				phone: { required: GetMessage('Validate','Phone') },
				email: { required: GetMessage('Validate','ValidEmail'), email: GetMessage('Validate','ValidEmail') },
				password: { required: GetMessage('Validate','WholeSalerPassword'), minlength: GetMessage('Validate','MinLengthPassword') },
				confirm_password: { required: GetMessage('Validate','WholeSalerValidConfirmPassword'), 
									  equalTo: GetMessage('Validate','WholeSalerValidConfirmPassword'), 
									  minlength: GetMessage('Validate','MinLengthConfirmPassword') 
								  },
				hear_about: { required: GetMessage('Register','HearAbout') },
				youtube: { required: GetMessage('Register','Youtube') },
				other: { required: GetMessage('Register','OtherText') },
				state: { required: GetMessage('Validate','State') },
				other_state: { required: GetMessage('Validate','OtherState') },
				einnumber: { required: GetMessage('Register','EINnumber') },
				salestax_id: { required: GetMessage('Register','SalesTaxID') },
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
						$("#frmWholesaleRegister #error_"+id).html(message);
						$("#frmWholesaleRegister #error_"+id).show();
					}
				} 
			   else 
			   {
				   $("#frmWholesaleRegister .frmerror").html('');
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
		
		if(!$('#frmWholesaleRegister').valid()) {
			return false;
		}
		
    });

});
