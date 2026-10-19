$('#formLogin input').keypress(function (e) 
{
	if (e.which == 13) 
	{
		submitform('formLogin');
		return false;
	}
});

$(document).ready(function()
{
	$("#btnLogin").click(function(){
		submitform('formLogin');
	});

	$("#formLogin").submit(function()
	{
		$("#formLogin .frmerror").html('');
		$('#formLogin').validate({
			rules: 
			{
				email: 	{ required: true,email: true },
				password: { required: true  }
			},
			messages: 
			{
				email: 	{ required: GetMessage('Login','ValidEmail'), email: GetMessage('Login','ValidEmail') },
				password: { required: GetMessage('Login','Password') }
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
						$("#formLogin #error_"+id).html(message);
						$("#formLogin #error_"+id).show();
					}
				} 
			   else 
			   {
				   $("#formLogin .frmerror").html('');
			   }
			},
			errorPlacement: function(error, element) 
			{
				// Override error placement to not show error messages beside elements //				
				if($("#formLogin #error_captcha_js").length > 0){					
					$('#formLogin #error_captcha_js').hide();
					if (grecaptcha.getResponse()=="") {					
						$('#formLogin #error_captcha_js').html("Please select I'm not a robot.");
						$('#formLogin #error_captcha_js').show();
						return false;						
					}
				}
			}     
		});
		
		if(!$('#formLogin').valid()) {
			return false;
		} else if($("#formLogin #error_captcha_js").length > 0){			
			$('#formLogin #error_captcha_js').hide();			
			if (grecaptcha.getResponse()=="") {
				$('#formLogin #error_captcha_js').html("Please select I'm not a robot.");
				$('#formLogin #error_captcha_js').show();
				return false;
			}
		}
    });

});
