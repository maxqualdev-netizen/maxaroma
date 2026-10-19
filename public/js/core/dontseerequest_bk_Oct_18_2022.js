$('#frmDontSeeIt input').keypress(function (e) {
	if (e.which == 13) {
		submitform('frmDontSeeIt');
		return false;
	}
});

$(document).ready(function () {
	$("#btnSubmitDontSeeIt").click(function () {
		submitform('frmDontSeeIt');
	});

	$("#btnResetDontSeeIt").click(function () {
		resetform('frmDontSeeIt');
	});

	$("#frmDontSeeIt").submit(function () {
		$("#frmDontSeeIt .frmerror").html('');
		$('#frmDontSeeIt').validate({
			rules:
			{
				custname: {
					required: true, maxlength: 15
				},

				email: {
					required: {
						depends: function () {
							$(this).val($.trim($(this).val()));
							return true;
						}
					},
					email: true
				},

				comments: {
					required: true
				},

				product_price: {
					required: true
				},

				product_qty: {
					required: true
				},

				delivery_date: {
					required: true
				},

				/*hiddenRecaptcha: {
					required: function () {
						if (grecaptcha.getResponse() == '') {
							return true;
						} else {
							return false;
						}
					}
				}*/


			},
			messages:
			{
				email: { required: GetMessage('DontSeeRequest', 'ValidEmail'), email: GetMessage('DontSeeRequest', 'ValidEmail') },
				custname: { required: GetMessage('DontSeeRequest', 'CustName') },
				comments: { required: GetMessage('DontSeeRequest', 'Comments') },
				product_price: { required: GetMessage('DontSeeRequest', 'ProductPrice') },
				product_qty: { required: GetMessage('DontSeeRequest', 'ProductQty') },
				delivery_date: { required: GetMessage('DontSeeRequest', 'DeliveryDate') },
			},
			onsubmit: false,
			invalidHandler: function (form, validator) {
				var errors = validator.numberOfInvalids();
				if (errors) {
					for (var i = 0; i < errors; i++) {
						var message = validator.errorList[i].message;
						var id = $(validator.errorList[i].element).attr('name');
						$("#frmDontSeeIt #error_" + id).html(message);
						$("#frmDontSeeIt #error_" + id).show();
					}
				}
				else {
					$("#frmDontSeeIt .frmerror").html('');
				}
			},
			errorPlacement: function (error, element) {
				// Override error placement to not show error messages beside elements //
				$('#error_captcha_js').hide();
				if (grecaptcha.getResponse() == "") {
					$('#error_captcha_js').html("Please select I'm not a robot.");
					$('#error_captcha_js').show();
					return false;
				}
			}
		});

		if (!$('#frmDontSeeIt').valid()) {
			return false;
		}

	});

	let file = "public/css/core/datepicker.css";
	$('head').append('<link rel="stylesheet" href="' + file + '" type="text/css" />');
	$("#delivery_date").attr("data-date", "mm/dd/yyyy");

	$("#delivery_date").on("change", function () {
		let file = "public/css/core/datepicker.css";
		$('head').append('<link rel="stylesheet" href="' + file + '" type="text/css" />');
		this.setAttribute(
			"data-date",
			moment(this.value, "YYYY-MM-DD")
				.format(this.getAttribute("data-date-format"))
		)
	});

});
