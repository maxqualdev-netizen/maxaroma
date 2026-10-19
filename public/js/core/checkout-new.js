$(document).ready(function() {
    AddressValidation();
});

function handlePlaceOrder()
{
    const btn = document.getElementById('place-order-btn');
    if (!btn) return;
    if($("#frmcheckoutaddress").valid())
    {
    }

    btn.classList.add('btn-loading');
    btn.setAttribute('aria-busy', 'true');
    btn.setAttribute('aria-label', 'Processing your order…');
    btn.disabled = true;

    // Simulate processing
    setTimeout(() => {
    btn.classList.remove('btn-loading');
    btn.disabled = false;
    // In real implementation: redirect to confirmation page
    }, 2000);
}

function AddressValidation()
{
    $("#frmcheckoutaddress").validate({
        rules: {
            shipping_first_name: {required: true},
            shipping_last_name: {required: true},
            shipping_address1: {required: true},
            shipping_city: {required: true},
            shipping_state: {required: true},
            shipping_zip: {required: true}
        },
        messages: {
            shipping_first_name: {required: "Please enter first name."},
            shipping_last_name: {required: "Please enter last name."},
            shipping_address1: {required: "Please enter address."},
            shipping_city: {required: "Please enter city."},
            shipping_state: {required: "Please enter state."},
            shipping_zip: {required: "Please enter zip code"}
        },
        invalidHandler: function(event, validator) {
            if (validator.errorList.length) {
                var firstErrorElement = validator.errorList[0].element;
                $('html, body').animate({
                    scrollTop: $(firstErrorElement).offset().top // Offset by 40px for spacing/fixed headers
                }, 500, function() {
                    $(firstErrorElement).focus();
                });
            }
        }
    });
}
