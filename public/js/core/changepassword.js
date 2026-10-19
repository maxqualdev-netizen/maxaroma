$('#frmChangePassword input').keypress(function (e) 
{
	if (e.which == 13) 
	{
		submitform('frmChangePassword');
		return false;
	}
});

$(document).ready(function()
{
	$("#btnChangePassword").click(function(){
		submitform('frmChangePassword');
	});

	jQuery.validator.addMethod("noSpace", function(value, element) {
	    return value.indexOf(" ") < 0 && value != "";
	}, "No space please and don't leave it empty");

	jQuery.validator.addMethod("passwordCheck", function(value, element, param) {
	    if (this.optional(element)) {
	        return true;
	    } else if (!/[A-Z]/.test(value)) {
	        return false;
	    } else if (!/[a-z]/.test(value)) {
	        return false;
	    } else if (!/[0-9]/.test(value)) {
	        return false;
	    }

	    return true;
    }, GetMessage('Validate','UpperCaseAndLetter'));

	$("#frmChangePassword").submit(function()
	{
		$("#frmChangePassword .frmerror").html('');
		$('#frmChangePassword').validate({
			rules: 
			{
				old_password: { required: true  },
				new_password: { required: true, minlength: 6, noSpace: true, passwordCheck: true  },
				re_type_new_password: { required: true, equalTo: '#new_password', noSpace: true, passwordCheck: true }
			},
			messages: 
			{
				old_password: { required: GetMessage('Validate','OldPassword') },
				new_password: { required: GetMessage('Validate','NewPassword'), 
								minlength: GetMessage('Validate','NewPassword'), 
								noSpace: GetMessage('Validate','NewPassword'), 
								passwordCheck: GetMessage('Validate','UpperCaseAndLetter') 
							},
				re_type_new_password: { required: GetMessage('Validate','ReTypeNewPassword'), 
									  	equalTo: GetMessage('Validate','ReTypeNewPassword'), 
									  	noSpace: GetMessage('Validate','ReTypeNewPassword'), 
									  	passwordCheck: GetMessage('Validate','UpperCaseAndLetter')
							  		}
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
						$("#frmChangePassword #error_"+id).html(message);
						$("#frmChangePassword #error_"+id).show();
					}
				} 
			   else 
			   {
				   $("#frmChangePassword .frmerror").html('');
			   }
			},
			errorPlacement: function(error, element) 
			{
				// Override error placement to not show error messages beside elements //
			}     
		});
		
		if(!$('#frmChangePassword').valid()) {
			return false;
		}
		
    });

});
