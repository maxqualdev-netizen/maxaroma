$(document).ready(function() {
	/*var country = $('#country').val();
	if(country == 'US') {
    	$("#divotherstate").hide();
	} else {
		$("#divstate").hide();
	}*/

	$('#country').on('change', function (e) {
	    var selectedCountry = $(this).val();
		if(selectedCountry == 'US') {
	    	$("#divotherstate").hide();
	    	$("#divstate").show();
		} else {
			$("#other_state").val('');
	    	$("#divotherstate").show();
	    	$("#divstate").hide();
		}
	});

	$(".remove_address").click(function(){
		var remove_id = $(this).attr('data-id');
		if (typeof remove_id  !== "undefined" && remove_id != '') {	
			if(confirm("Are you sure, You want to Delete?"))
			{	
				$.ajax({
					type: 'DELETE',
					url: site_url+'addressbook/remove.html',
					data: { 'id' : remove_id },
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					beforeSend: function() 
					{
					},
					success: (function(data) {
						if(data.status == 200) {
							$("#success-message").hide();
							$("#delete-success-message").show();
							$("#addressbook_block_"+remove_id).remove();
							$("#frmChangePassword #error_"+id).show();
						}
			       })
				});
			}
		} else {
			alert("Something went wrong");
			return false;
		}
	});

});


$('#frmAddAddressBook input').keypress(function (e) 
{
	if (e.which == 13) 
	{
		submitform('frmAddAddressBook');
		return false;
	}
});

$(document).ready(function()
{
	/* Edit Address Book starts */
	
	$("#btnAddAddressBook").click(function(){
		submitform('frmAddAddressBook');
	});

	jQuery.validator.addMethod('phoneUS', function (value, element){
 
		return this.optional(element) || /^[0-9 +-]+$/.test(value);
	   
	  }, "Please enter a valid phone number");

	$("#frmAddAddressBook").submit(function()
	{
		$("#frmAddAddressBook .frmerror").html('');
		$('#frmAddAddressBook').validate({
			rules: 
			{
				first_name: { required: true  },
				last_name: { required: true  },
				address1: { required: true  },
				city: { required: true  },
				country: { required: true  },
				zip: { required: true  },
				email: 	{ required: true,email: true },
				phone: {
					required: true,
					minlength: 10,
					phoneUS: true
				},
				state: { required: 
									function(){
				                        return $("#country").val() == "US"
				                  	}
				},
				other_state: { required: 
									function(){
				                        return $("#country").val() != "US"
				                  	}
				}
			},
			messages: 
			{
				first_name: 	{ required: GetMessage('Validate','FirstName') },
				last_name: 	{ required: GetMessage('Validate','LastName') },
				address1: 	{ required: GetMessage('Validate','Address') },
				city: 	{ required: GetMessage('Validate','City') },
				country: 	{ required: GetMessage('Validate','Country') },
				zip: 	{ required: GetMessage('Validate','ZipCode') },
				phone: 	{ required: GetMessage('Validate','Phone') },
				email: 	{ required: GetMessage('Validate','ValidEmail'), email: GetMessage('Validate','ValidEmail') },
				state: 	{ required: GetMessage('Validate','State') },
				other_state: 	{ required: GetMessage('Validate','OtherState') }
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
						$("#frmAddAddressBook #error_"+id).html(message);
						$("#frmAddAddressBook #error_"+id).show();
					}
				} 
			   else 
			   {
				   $("#frmAddAddressBook .frmerror").html('');
			   }
			},
			errorPlacement: function(error, element) 
			{
				// Override error placement to not show error messages beside elements //
			}     
		});
		
		if(!$('#frmAddAddressBook').valid()) {
			return false;
		}
		
    });

	/* Edit Address Book starts */

	$("#btnEditAddressBook").click(function(){
		submitform('frmEditAddressBook');
	});

	$("#frmEditAddressBook").submit(function()
	{
		$("#frmEditAddressBook .frmerror").html('');
		$('#frmEditAddressBook').validate({
			rules: 
			{
				first_name: { required: true  },
				last_name: { required: true  },
				address1: { required: true  },
				city: { required: true  },
				country: { required: true  },
				zip: { required: true  },
				phone: { required: true  },
				email: 	{ required: true,email: true },
				state: { required: 
									function(){
				                        return $("#country").val() == "US"
				                  	}
				},
				other_state: { required: 
									function(){
				                        return $("#country").val() != "US"
				                  	}
				}
			},
			messages: 
			{
				first_name: 	{ required: GetMessage('Validate','FirstName') },
				last_name: 	{ required: GetMessage('Validate','LastName') },
				address1: 	{ required: GetMessage('Validate','Address') },
				city: 	{ required: GetMessage('Validate','City') },
				country: 	{ required: GetMessage('Validate','Country') },
				zip: 	{ required: GetMessage('Validate','ZipCode') },
				phone: 	{ required: GetMessage('Validate','Phone') },
				email: 	{ required: GetMessage('Validate','ValidEmail'), email: GetMessage('Validate','ValidEmail') },
				state: 	{ required: GetMessage('Validate','State') },
				other_state: 	{ required: GetMessage('Validate','OtherState') }
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
						$("#frmEditAddressBook #error_"+id).html(message);
						$("#frmEditAddressBook #error_"+id).show();
					}
				} 
			   else 
			   {
				   $("#frmEditAddressBook .frmerror").html('');
			   }
			},
			errorPlacement: function(error, element) 
			{
				// Override error placement to not show error messages beside elements //
			}     
		});
		
		if(!$('#frmEditAddressBook').valid()) {
			return false;
		}
		
    });

});
