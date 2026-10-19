$('#frmContactUs input').keypress(function (e) 
{
	if (e.which == 13) 
	{
		submitform('frmContactUs');
		return false;
	}
});

$(document).ready(function()
{
	$("#btnContactUsReset").click(function(){
		resetform('frmContactUs');
	});

	$("#btnContactUs").click(function(){
		submitform('frmContactUs');
	});

	$("#frmContactUs").submit(function()
	{
		$("#frmContactUs .frmerror").html('');
		$('#frmContactUs').validate({
			ignore: ".ignore",
			rules: 
			{
				name: { required: true  },
				email: { required: true, email: true },
				subject: { required: true  },
				description: { required: true  },
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
				name: 	{ required: GetMessage('ContactUs','YourName') },
				email: 	{ required: GetMessage('ContactUs','YourEmail') },
				subject: 	{ required: GetMessage('ContactUs','YourSubject') },
				description: 	{ required: GetMessage('ContactUs','YourComment') },
				hiddenRecaptcha: 	{ required: "Please select I'm not a robot." },
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
						$("#frmContactUs #error_"+id).html(message);
						$("#frmContactUs #error_"+id).show();
					}
				} 
			   else 
			   {
				   $("#frmContactUs .frmerror").html('');
			   }
			},
			errorPlacement: function(error, element) 
			{
				
				if(element.attr('name') == 'hiddenRecaptcha'){
				// Override error placement to not show error messages beside elements //
				//$('#error_captcha_js').hide();
					/*if (grecaptcha.getResponse()=="") {*/
					error.appendTo($('#error_captcha_js')); 
						$('#error_captcha_js').html("Please select I'm not a robot.");
						$('#error_captcha_js').show();
						
						
						/*return false;
					}*/
				}
			}     
		});
		
		if(!$('#frmContactUs').valid()) {
			return false;
		}
		
    });

});
