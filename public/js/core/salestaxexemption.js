$('#frmsalesTaxExemption input').keypress(function (e) 
{
	if (e.which == 13) 
	{
		submitform('frmsalesTaxExemption');
		return false;
	}
});

$(document).ready(function()
{
	$("#btnSalesTaxExemptionReset").click(function(){
		resetform('frmsalesTaxExemption');
	});

	$("#btnSalesTaxExemption").click(function(){
		submitform('frmsalesTaxExemption');
	});

	
	$("#frmsalesTaxExemption").submit(function()
	{
		$("#frmsalesTaxExemption .frmerror").html('');
		$('#frmsalesTaxExemption').validate({
			ignore: ".ignore",
			rules: 
			{
				first_name: { required: true  },
				last_name: { required: true  },
				email_id: 	{ required: true,email: true },
				state_for_exemption: { required: true  },
				upload_sales_tax: { required: true  },
			},
			messages: 
			{
				first_name: { required: GetMessage('Validate','FirstName') },
				last_name: { required: GetMessage('Validate','LastName') },
				email_id: { required: GetMessage('Validate','ValidEmail'), email: GetMessage('Validate','ValidEmail') },
				state_for_exemption: 	{ required: "Please enter state for exemption." },
				upload_sales_tax: 	{ required: "Please upload certificate." },
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
						$("#frmsalesTaxExemption #error_"+id).html(message);
						$("#frmsalesTaxExemption #error_"+id).show();
					}
				} 
			   else 
			   {
				   $("#frmsalesTaxExemption .frmerror").html('');
			   }
			},
			errorPlacement: function(error, element) 
			{
			}
		});
		
		if(!$('#frmsalesTaxExemption').valid()) {
			return false;
		}
		
    });

});
