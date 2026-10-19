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
			ignore: ".ignore",
			rules: 
			{
				bill_email: 	{ required: true,email: true },
				orders_id: { required: true  },
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
				bill_email: 	{ required: GetMessage('TrackOrder','ValidEmail'), email: GetMessage('TrackOrder','ValidEmail') },
				orders_id: { required: GetMessage('TrackOrder','OrderID') },
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
				/*if(element.attr('name') == 'hiddenRecaptcha'){
					error.appendTo($('#error_captcha_js')); 
					$('#error_captcha_js').html("Please select I'm not a robot.");
					$('#error_captcha_js').show();
				}*/
				$('#error_captcha_js').hide();
				if (grecaptcha.getResponse(0)=="") {					
					$('#error_captcha_js').html("Please select I'm not a robot.");
					$('#error_captcha_js').show();
					return false;
				}
			}
		});
		
		if(!$('#formTrackOrder').valid()) {
			return false;
		} else if($("#formTrackOrder #error_captcha_js").length > 0){						
			$('#formTrackOrder #error_captcha_js').hide();			
			if (grecaptcha.getResponse(0)=="") {								
				$('#formTrackOrder #error_captcha_js').html("Please select I'm not a robot.");
				$('#formTrackOrder #error_captcha_js').show();
				return false;
			}
		}
		
    });

});
