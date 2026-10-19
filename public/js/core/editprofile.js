var eusertype = $("#eusertype").val();

$(document).ready(function() {
	/* Hide/Show State and Other State Div */
	$("#country").change(function(){
		if($(this).val() != 'US') {
			$("#divstate").hide();
			$("#divotherstate").show();
		} else {
			$("#divotherstate").hide();
			$("#divstate").show();
		}
	});

	/* Hide/Show EIN Number Div */
	$('#state').on('change', function (e) {
		if(eusertype == 'Wholesaler') {
		    var selectedState = $(this).val();
			if(selectedState == 'NY') {
		    	$("#diveinnumber").show();
			} else {
		    	$("#diveinnumber").hide();
			}
		}
	});
});


$('#frmEditProfile input').keypress(function (e) 
{
	if (e.which == 13) 
	{
		submitform('frmEditProfile');
		return false;
	}
});

$(document).ready(function()
{
	$("#btnEditProfile").click(function(){
		submitform('frmEditProfile');
	});

	$("#frmEditProfile").submit(function()
	{
		$("#frmEditProfile .frmerror").html('');
		$('#frmEditProfile').validate({
			rules: 
			{
				first_name: { required: true  },
				last_name: { required: true  },
				address1: { required: true  },
				city: { required: true  },
				country: { required: true  },
				zip: { required: true  },
				phone: { required: true  },
				state: { required: 
									function(){
				                        return $("#country").val() == "US"
				                  	}
				},
				other_state: { required: 
									function(){
				                        return $("#country").val() != "US"
				                  	}
				},
				einnumber: { required: 
									function(){
				                        return $("#state").val() == "NY" && eusertype == 'Wholesaler'
				                  	}
				},
				/*salestax_id: { required: 
									function(){
				                        return $("#state").val() == "NY"
				                  	}
				}*/
			},
			messages: 
			{
				first_name: { required: GetMessage('Validate','FirstName') },
				last_name: { required: GetMessage('Validate','LastName') },
				address1: { required: GetMessage('Validate','Address') },
				city: { required: GetMessage('Validate','City') },
				country: { required: GetMessage('Validate','Country') },
				zip: { required: GetMessage('Validate','ZipCode') },
				phone: { required: GetMessage('Validate','Phone') },
				state: { required: GetMessage('Validate','State') },
				other_state: { required: GetMessage('Validate','OtherState') },
				einnumber: { required: GetMessage('Register','EINnumber') },
				/*salestax_id: { required: GetMessage('Register','SalesTaxID') }*/
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
						$("#frmEditProfile #error_"+id).html(message);
						$("#frmEditProfile #error_"+id).show();
					}
				} 
			   else 
			   {
				   $("#frmEditProfile .frmerror").html('');
			   }
			},
			errorPlacement: function(error, element) 
			{
				// Override error placement to not show error messages beside elements //
			}     
		});
		
		if(!$('#frmEditProfile').valid()) {
			return false;
		}
		
    });

});

/* When change the date */
$("#birthday").on("change", function() {
	let file = "public/css/core/datepicker.css";
	$('head').append('<link rel="stylesheet" href="'+ file +'" type="text/css" />');
    this.setAttribute(
        "data-date",
        moment(this.value, "YYYY-MM-DD")
        .format( this.getAttribute("data-date-format") )
    )
});

/* If date is not selected */
let birthday = $("#birthday").val();
if(birthday !== '') {
	let newFormat = $("#birthday").attr('data-date-format');
	console.log('newFormat : '+newFormat)
	$("#birthday").attr('data-date', moment(birthday, "YYYY-MM-DD").format( newFormat ));
	let file = "public/css/core/datepicker.css";
	$('head').append('<link rel="stylesheet" href="'+ file +'" type="text/css" />');
} else {
	$("#birthday").attr("data-date", "mm/dd/yyyy");
}
