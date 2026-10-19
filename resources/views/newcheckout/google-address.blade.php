<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCcdWTGp2vy5_cEjzW6VdBadPer4CUKM3Q&libraries=places&callback=initAutocomplete&loading=async" async></script>
<script>
const addressForm = {
    street_number: 'short_name',
    route: 'long_name',
    subpremise: 'short_name',
    locality: 'long_name',
    postal_town: 'long_name',
    administrative_area_level_1: 'short_name',
    administrative_area_level_2: 'long_name',
    country: 'short_name',
    postal_code: 'short_name'
};

let autocompletes = {};

function initAutocomplete() {
    ['billing_address1', 'shipping_address1'].forEach(function(id) {
        const input = document.getElementById(id);
        if (!input) return;

        const country = (id === 'billing_address1')
            ? $('#billing_country').val() || 'US'
            : $('#shipping_country').val() || 'US';

        const autocomplete = new google.maps.places.Autocomplete(input, {
            types: ['address'],
            fields: ['address_components'],
            componentRestrictions: { country: country.toLowerCase() }
        });

        autocompletes[id] = autocomplete;

        autocomplete.addListener('place_changed', () => {
            const place = autocomplete.getPlace();
            fillAddressFields(place, id);
            $(input).data('place-selected', true);
        });

        $(input).on('blur', function () {
            const hasValue = $(this).val().trim() !== '';
            const placeSelected = $(this).data('place-selected');
            if (hasValue && !placeSelected) {
                const service = new google.maps.places.AutocompleteService();
                service.getPlacePredictions({
                    input: $(this).val(),
                    types: ['address'],
                    componentRestrictions: { country: country.toLowerCase() }
                }, (predictions, status) => {
                    if (status === google.maps.places.PlacesServiceStatus.OK && predictions.length > 0) {
                        const placeId = predictions[0].place_id;
                        const placesService = new google.maps.places.PlacesService(document.createElement('div'));
                        placesService.getDetails({
                            placeId: placeId,
                            fields: ['address_components']
                        }, (place, detailStatus) => {
                            if (detailStatus === google.maps.places.PlacesServiceStatus.OK) {
                                fillAddressFields(place, id);
                            }
                        });
                    }
                });
            }
            $(this).data('place-selected', false);
        });
    });
}

$(document).on('change', '#billing_country, #shipping_country', function () {
    const id = this.id === 'billing_country' ? 'billing_address1' : 'shipping_address1';
    const country = $(this).val() || 'US';
    if (autocompletes[id]) {
        autocompletes[id].setComponentRestrictions({ country: country.toLowerCase() });
    }
});

function updateInputDivClass(el) {
    const $el = $(el);
    const formGroup = $el.closest('.form-group');

    if ($el.val().trim() !== '') {
        formGroup.addClass('input-fcs');
    } else {
        formGroup.removeClass('input-fcs');
    }
}

const addressFields = [
    '#billing_email','#billing_address1', '#billing_address2', '#billing_city', '#billing_zip', '#billing_state', '#billing_other_state',
    '#shipping_address1', '#shipping_address2', '#shipping_city', '#shipping_zip', '#shipping_state', '#shipping_other_state'
];

function applyInputClassToAll() {
    // addressFields.forEach(selector => {
    //     console.log(selector);
    //     updateInputDivClass($(selector));
    // });
}

function fillAddressFields(place,srcId){
    if (!place || !place.address_components) return;
    console.log('load formatted address');
    const places_data = {};
    place.address_components.forEach(component => {
        const addressType = component.types[0];
        if (addressForm[addressType]) {
            places_data[addressType] = component[addressForm[addressType]];
        }
    });

    const address_line1 = [places_data.street_number, places_data.route].filter(Boolean).join(' ').trim();
    const address_line2 = places_data.subpremise || '';
    const address_city = places_data.locality || places_data.postal_town || places_data.administrative_area_level_2 || '';
    const address_state = places_data.administrative_area_level_1 || '';
    const address_zip = places_data.postal_code || '';
    const address_country = places_data.country || '';

    if(srcId == "billing_address1"){
        $("#billing_address1").val(address_line1);
        $("#billing_address2").val(address_line2);
        $("#billing_city").val(address_city);
        $("#billing_country").val(address_country);

        $("#billing_zip").val(address_zip);

        if(address_country != "US"){
            $("#divstate").hide();
            $("#divotherstate").show();
            $("#billing_other_state").val(address_state);
        } else {
            $("#billing_state").val(address_state);
            $("#billing_other_state").val("");
            $("#divotherstate").hide();
            $("#divstate").show();
        }
    } else {
        $("#shipping_address1").val(address_line1);
        $("#shipping_address2").val(address_line2);
        $("#shipping_city").val(address_city);
        $("#shipping_country").val(address_country);
        $("#shipping_zip").val(address_zip);

        if(address_country != "US"){
            $("#divshipstate").hide();
            $("#divshipotherstate").show();
            $("#shipping_other_state").val(address_state);
        } else {
            $("#shipping_state").val(address_state);
            $("#shipping_other_state").val("");
            $("#divshipotherstate").hide();
            $("#divshipstate").show();
        }
    }
    window.MaxaromaOnePageCheckout.loadShippingMethods();
    applyInputClassToAll();
}
</script>