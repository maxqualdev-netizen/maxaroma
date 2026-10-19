$('#formForgotPassword input').keypress(function (e) 
{
	if (e.which == 13) 
	{
		submitform('formForgotPassword');
		return false;
	}
});

$(document).ready(function()
{
	$("#btnForgotPassword").click(function(){
		submitform('formForgotPassword');
	});

	$("#formForgotPassword").submit(function()
	{
		$("#formForgotPassword .frmsuccess").html('');
		$("#formForgotPassword .frmerror").html('');
		$('#formForgotPassword').validate({
			rules: 
			{
				email: 	{ required: true,email: true }
			},
			messages: 
			{
				email: 	{ required: GetMessage('Forgot','ValidEmail'), email: GetMessage('Forgot','ValidEmail') }
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
						$("#formForgotPassword #error_"+id).html(message);
						$("#formForgotPassword #error_"+id).show();
					}
				} 
			   else 
			   {
				   $("#formForgotPassword .frmerror").html('');
			   }
			},
			errorPlacement: function(error, element) 
			{
				// Override error placement to not show error messages beside elements //
				$('#error_captcha_js').hide();
				if (grecaptcha.getResponse()=="") {
					$('#error_captcha_js').html("Please select I'm not a robot.");
					$('#error_captcha_js').show();
					return false;
				}
			}     
		});
		
		if(!$('#formForgotPassword').valid()) {
			return false;
		} else if($("#formForgotPassword #error_captcha_js").length > 0){			
			$('#formForgotPassword #error_captcha_js').hide();			
			if (grecaptcha.getResponse()=="") {				
				$('#formForgotPassword #error_captcha_js').html("Please select I'm not a robot.");
				$('#formForgotPassword #error_captcha_js').show();
				return false;
			}			
		}
		
    });

});
