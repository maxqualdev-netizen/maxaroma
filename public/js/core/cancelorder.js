
function otherField(val)
{
 $("#OtherInput").hide();
 if(val == "Other") {
	$("#OtherInput").show();
 }
}

$('#myModalPopUpCancelOrder').on('hidden.bs.modal', function () {
 location.reload();
})

$('#frmCancelOrder input').keypress(function (e) 
{
	if (e.which == 13) 
	{
		submitform('frmCancelOrder');
		return false;
	}
});

$(document).ready(function()
{
	$("#btnCancelOrder").click(function(){
		submitform('frmCancelOrder');
	});

	$("#frmCancelOrder").submit(function()
	{
		$("#frmCancelOrder .frmerror").html('');
		$('#frmCancelOrder').validate({
			rules: 
			{
				customerCancelReason: 	{ required: true },
				otherreason: { required: 
					function(){
                        return $("#customerCancelReason").val() == "Other"
                  	}
				},
			},
			messages: 
			{
				customerCancelReason: 	{ required: GetMessage('CancelOrder','CancelReason') },
				otherreason: 	{ required: GetMessage('CancelOrder','OtherCancelReason') },
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
						$("#frmCancelOrder #error_"+id).html(message);
						$("#frmCancelOrder #error_"+id).show();
					}
				} 
			   else 
			   {
				   $("#frmCancelOrder .frmerror").html('');
			   }
			},
			errorPlacement: function(error, element) 
			{
				// Override error placement to not show error messages beside elements //
			}     
		});
		
		if(!$('#frmCancelOrder').valid()) {
			return false;
		}
		
    });

});
