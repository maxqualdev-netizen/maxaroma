$('#checkAll').click(function () {    
	$('input:checkbox').prop('checked', this.checked);    
});

$('#btnDeleteWishCategory').click(function () {    
	var total=$('#frmwish').find('input[name="ch[]"]:checked').length;
	if (total>0)
	{	
		rtn=confirm("Delete Selected Record ?");
		if(rtn==false)
		{	
			return false;	
		}
		else
		{	
			document.frmwish.action.value="DeleteCat";
			submitform('frmwish');	
		}
	}
	else
	{
		alert("Please select Checkbox.");
		return false;
	}
});

$('#frmWishCategoryEdit input').keypress(function (e) 
{
	if (e.which == 13) 
	{
		submitform('frmWishCategoryEdit');
		return false;
	}
});

$(document).ready(function()
{
	/* Edit Wish Category starts */
	
	$("#btnEditWishCategory").click(function(){
		submitform('frmWishCategoryEdit');
	});

	$("#frmWishCategoryEdit").submit(function()
	{
		$("#frmWishCategoryEdit .frmerror").html('');
		$('#frmWishCategoryEdit').validate({
			rules: 
			{
				name: { required: true  },
				description: { required: true  }
			},
			messages: 
			{
				name: 	{ required: GetMessage('WishCategory','Name') },
				description: 	{ required: GetMessage('WishCategory','Description') }
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
						$("#frmWishCategoryEdit #error_"+id).html(message);
						$("#frmWishCategoryEdit #error_"+id).show();
					}
				} 
			   else 
			   {
				   $("#frmWishCategoryEdit .frmerror").html('');
			   }
			},
			errorPlacement: function(error, element) 
			{
				// Override error placement to not show error messages beside elements //
			}     
		});
		
		if(!$('#frmWishCategoryEdit').valid()) {
			return false;
		}
		
    });

});