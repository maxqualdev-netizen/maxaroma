$('#frmContactUs input').keypress(function (e) 
{
	if (e.which == 13) 
	{
		submitform('frmContactUs');
		return false;
	}
});

$(document).ready(function () {

    // Reset form button
    $("#btnContactUsReset").click(function () {
        resetform('frmContactUs');
    });

    // Custom form submission triggered by button
    $("#btnContactUs").click(function (e) {
        e.preventDefault();
        $("#frmContactUs").submit(); // triggers jQuery Validate
    });

    // Form submission handler
    $("#frmContactUs").validate({
        ignore: ".ignore",
        rules: {
            name: { required: true },
            email: { required: true, email: true },
            subject: { required: true },
            description: { required: true },
            hiddenRecaptcha: {
                required: function () {
                    return grecaptcha.getResponse() === '';
                }
            }
        },
        messages: {
            name: { required: GetMessage('ContactUs', 'YourName') },
            email: { required: GetMessage('ContactUs', 'YourEmail') },
            subject: { required: GetMessage('ContactUs', 'YourSubject') },
            description: { required: GetMessage('ContactUs', 'YourComment') },
            hiddenRecaptcha: { required: "Please select I'm not a robot." }
        },
        invalidHandler: function (form, validator) {
            var errors = validator.numberOfInvalids();
            if (errors) {
                for (var i = 0; i < errors; i++) {
                    var message = validator.errorList[i].message;
                    var id = $(validator.errorList[i].element).attr('name');
                    $("#error_" + id).html(message).show();
                }
            } else {
                $(".frmerror").html('');
            }
        },
        errorPlacement: function (error, element) {
            if (element.attr('name') === 'hiddenRecaptcha') {
                $('#error_captcha_js').html("Please select I'm not a robot.").show();
            }
        },
        submitHandler: function (form, event) {
			$("#page-spinner").show();
            if ($("#edesk_sub").val() === "0") {
                $.ajax({
                    url: site_url + 'edesk-contact-us',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    type: 'POST',
                    data: {
                        name: $("#name").val(),
                        email: $("#email").val(),
                        subject: $("#subject").val(),
                        order_number: $("#00N6g00000SdQCZ").val(),
                        description: $("#description").val()
                    },
                    success: function (response) {
                        $("#edesk_sub").val("1");
						$("#page-spinner").hide();
						$("#frmContactUs .frmerror").html('');
                        $("#ThankyouMessage").removeClass("d-none");
                        $("#frmContactUs")[0].reset(); // Optional: reset form
                        grecaptcha.reset(); // Optional: reset reCAPTCHA
                    },
                    error: function (xhr) {
                        alert("Something went wrong. Please try again.");
                        console.error(xhr.responseText);
                    }
                });
            }
        }
    });

});


// $(document).ready(function()
// {
// 	$("#btnContactUsReset").click(function(){
// 		resetform('frmContactUs');
// 	});

// 	$("#btnContactUs").click(function(){
// 		submitform('frmContactUs');
// 	});

// 	$("#frmContactUs").submit(function()
// 	{
// 		$("#frmContactUs .frmerror").html('');
// 		$('#frmContactUs').validate({
// 			ignore: ".ignore",
// 			rules: 
// 			{
// 				name: { required: true  },
// 				email: { required: true, email: true },
// 				subject: { required: true  },
// 				description: { required: true  },
// 				hiddenRecaptcha: {
// 					required: function () {
// 						if (grecaptcha.getResponse() == '') {
// 							return true;
// 						} else {
// 							return false;
// 						}
// 					}
// 				}
// 			},
// 			messages: 
// 			{
// 				name: 	{ required: GetMessage('ContactUs','YourName') },
// 				email: 	{ required: GetMessage('ContactUs','YourEmail') },
// 				subject: 	{ required: GetMessage('ContactUs','YourSubject') },
// 				description: 	{ required: GetMessage('ContactUs','YourComment') },
// 				hiddenRecaptcha: 	{ required: "Please select I'm not a robot." },
// 			},
// 			onsubmit: false,
// 			invalidHandler: function(form, validator) 
// 			{
// 				var errors = validator.numberOfInvalids();
// 				if (errors) 
// 				{
// 					for(var i=0;i<errors;i++)
// 					{
// 						var message = validator.errorList[i].message;
// 						var id = $(validator.errorList[i].element).attr('name');
// 						$("#frmContactUs #error_"+id).html(message);
// 						$("#frmContactUs #error_"+id).show();
// 					}
// 				} 
// 			   else 
// 			   {
// 				   $("#frmContactUs .frmerror").html('');
// 			   }
// 			},
// 			errorPlacement: function(error, element) 
// 			{
				
// 				if(element.attr('name') == 'hiddenRecaptcha'){
// 				// Override error placement to not show error messages beside elements //
// 				//$('#error_captcha_js').hide();
// 					/*if (grecaptcha.getResponse()=="") {*/
// 					error.appendTo($('#error_captcha_js')); 
// 						$('#error_captcha_js').html("Please select I'm not a robot.");
// 						$('#error_captcha_js').show();
						
						
// 						/*return false;
// 					}*/
// 				}
// 			}     
// 		});
		
// 		if(!$('#frmContactUs').valid()) {
// 			return false;
// 		}
		
//     });

// });
