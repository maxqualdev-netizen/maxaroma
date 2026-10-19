$('#formreset input').keypress(function (e) 
{
	if (e.which == 13) 
	{
		submitform('formreset');
		return false;
	}
});

$(document).ready(function()
{
	$("#btnReset").click(function(){
		submitform('formreset');
	});
    jQuery.validator.addMethod("noSpace", function(value, element) { 
	   return value.indexOf(" ") < 0 && value != ""; 
	}, "No space please and don't leave it empty");
	$("#formreset").submit(function()
	{
		$("#formreset .frmerror").html('');
		$('#formreset').validate({
			rules: 
			{
				password: { required: true, 
							noSpace: true,
							minlength: 6
							},
				confirm_Password: { required: true, equalTo: '#password' },
			},
			messages: 
			{
				password: { required: GetMessage('Register','Password') },
				confirm_Password: 	{ required: GetMessage('Validate','ValidConfirmPassword'), equalTo: GetMessage('Validate','ValidConfirmPassword') },
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
						$("#formreset #error_"+id).html(message);
						$("#formreset #error_"+id).show();
					}
				} 
			   else 
			   {
				   $("#formreset .frmerror").html('');
			   }
			},
			errorPlacement: function(error, element) 
			{
				
			}     
		});
		
		if(!$('#formreset').valid()) {
			return false;
		}
		
    });

});
