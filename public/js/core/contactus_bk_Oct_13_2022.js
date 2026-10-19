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
			rules: 
			{
				your_name: { required: true  },
				your_email: { required: true, email: true },
				your_subject: { required: true  },
				your_comment: { required: true  }
			},
			messages: 
			{
				your_name: 	{ required: GetMessage('ContactUs','YourName') },
				your_email: 	{ required: GetMessage('ContactUs','YourEmail') },
				your_subject: 	{ required: GetMessage('ContactUs','YourSubject') },
				your_comment: 	{ required: GetMessage('ContactUs','YourComment') }
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
				// Override error placement to not show error messages beside elements //
				$('#error_captcha_js').hide();
				if (grecaptcha.getResponse()=="") {
					$('#error_captcha_js').html("Please select I'm not a robot.");
					$('#error_captcha_js').show();
					return false;
				}
			}     
		});
		
		if(!$('#frmContactUs').valid()) {
			return false;
		}
		
    });

});
