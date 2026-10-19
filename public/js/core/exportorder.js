$('#frmExportOrder input').keypress(function (e) 
{
	if (e.which == 13) 
	{
		submitform('frmExportOrder');
		return false;
	}
});

$(document).ready(function()
{
	$("#btnSearch").click(function(){
		submitform('frmExportOrder');
	});

	$("#btnExportOrderReset").click(function(){
		resetform('frmExportOrder');
	});

	$("#frmExportOrder").submit(function()
	{
		$("#frmExportOrder .frmerror").html('');
		$('#frmExportOrder').validate({
			rules: 
			{
				d_start_date: { required: true,
								date: true},
				d_end_date: { required: true,
								date: true }
			},
			messages: 
			{
				d_start_date: { required: GetMessage('ExportOrder','FromDate') },
				d_end_date: { required: GetMessage('ExportOrder','ToDate') },
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
						$("#frmExportOrder #error_"+id).html(message);
						$("#frmExportOrder #error_"+id).show();
					}
				} 
			   else 
			   {
				   $("#frmExportOrder .frmerror").html('');
			   }
			},
			errorPlacement: function(error, element) 
			{
				// Override error placement to not show error messages beside elements //
			}     
		});
		
		if(!$('#frmExportOrder').valid()) {
			return false;
		}
		
    });

});
