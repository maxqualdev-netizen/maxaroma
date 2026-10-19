$('#formTrackOrder input').keypress(function (e) 
{
	if (e.which == 13) 
	{
		submitform('formTrackOrder');
		return false;
	}
});

$(document).ready(function()
{
	$("#btnTrackOrder").click(function(){
		submitform('formTrackOrder');
	});

	$("#formTrackOrder").submit(function()
	{
		$("#formTrackOrder .frmerror").html('');
		$('#formTrackOrder').validate({
			rules: 
			{
				bill_email: 	{ required: true,email: true },
				orders_id: { required: true  }
			},
			messages: 
			{
				bill_email: 	{ required: GetMessage('TrackOrder','ValidEmail'), email: GetMessage('TrackOrder','ValidEmail') },
				orders_id: { required: GetMessage('TrackOrder','OrderID') }
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
						$("#formTrackOrder #error_"+id).html(message);
						$("#formTrackOrder #error_"+id).show();
					}
				} 
			   else 
			   {
				   $("#formTrackOrder .frmerror").html('');
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
		
		if(!$('#formTrackOrder').valid()) {
			return false;
		}
		
    });

});
