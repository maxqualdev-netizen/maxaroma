$('#frmImportOrder input').keypress(function (e) 
{
	if (e.which == 13) 
	{
		submitform('frmImportOrder');
		return false;
	}
});

$(document).ready(function()
{
	$("#btnImportOrder").click(function(){
		submitform('frmImportOrder');
	});

	$("#btnImportOrderReset").click(function(){
		resetform('frmImportOrder');
	});

	$("#frmImportOrder").submit(function()
	{
		$("#frmImportOrder .frmerror").html('');
		$('#frmImportOrder').validate({
			rules: 
			{
				fileupload: { required: true, 
								extension: 'csv', 
								accept : 'text/csv,text/comma-separated-value,application/vnd.ms-excel,application/vnd.msexcel,application/csv' 
							}
			},
			messages: 
			{
				fileupload: { required: GetMessage('ImportOrder','CSV'), extension: GetMessage('ImportOrder','AcceptCSV'), accept: GetMessage('ImportOrder','AcceptCSV') }
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
						$("#frmImportOrder #error_"+id).html(message);
						$("#frmImportOrder #error_"+id).show();
					}
				} 
			   else 
			   {
				   $("#frmImportOrder .frmerror").html('');
			   }
			},
			errorPlacement: function(error, element) 
			{
				// Override error placement to not show error messages beside elements //
			}     
		});
		
		if(!$('#frmImportOrder').valid()) {
			return false;
		}
		
    });

});
