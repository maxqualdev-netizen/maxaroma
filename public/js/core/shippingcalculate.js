$(document).ready(function () {

	$("#country").change(function () {
		if ($(this).val() != 'US') {
			$("#divstate").hide();
			$("#divotherstate").show();
		} else {
			$("#divotherstate").hide();
			$("#divstate").show();
		}
	});

});

$('#shippingfield').validate({
	rules:
	{
		zip: { required: true }
	},
	messages:
	{
		zip: { required: GetMessage('Validate', 'ZipCode') }
	},
	invalidHandler: function (form, validator) {
		$("#shippingfield .frmerror").html('');
		var errors = validator.numberOfInvalids();
		if (errors) {
			for (var i = 0; i < errors; i++) {
				var message = validator.errorList[i].message;
				var id = $(validator.errorList[i].element).attr('name');
				$("#shippingfield #error_" + id).html(message);
				$("#shippingfield #error_" + id).show();
			}
		}
		else {
			$("#shippingfield .frmerror").html('');
		}
	},
	errorPlacement: function (error, element) {
		// Override error placement to not show error messages beside elements //
	},
	submitHandler: function (form) {
		var zip = document.getElementById("zip").value;
		var country = document.getElementById("country").value;
		var state = document.getElementById("state").value;
		var check_value = document.getElementById("value_check").value;
		var str = "zip=" + zip + "&country=" + country + '&state=' + state + '&value_check=' + check_value;

		$.ajax({
			type: "POST",
			url: site_url + "shipping_calculate",
			data: str,
			cache: false,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success: function (data) {
				$("#cover-spin").hide();
				$('#show_shipping_method_box').html('');
				$('#show_shipping_method_box').html(data.shipppingfield);
			},
			error: function (err) {
				$('#error_zip').text('');
				$('#error_zip').text(err.responseJSON.errors.zip);
				$("#error_zip").show();
			}
		});
	}
});