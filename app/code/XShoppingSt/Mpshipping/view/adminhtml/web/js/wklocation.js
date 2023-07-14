require(
    [
        'jquery',
        'mage/translate',
    ],
    function ($) {
        $("#carriers_xShoppingStShipping_latitude").attr('readonly','readonly');
        $("#carriers_xShoppingStShipping_longitude").attr('readonly','readonly');

        var autocomplete = new google.maps.places.Autocomplete($("#carriers_xShoppingStShipping_location")[0], {});
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            var place = autocomplete.getPlace();
            $("#carriers_xShoppingStShipping_latitude").val(place.geometry.location.lat().toFixed(5));
            $("#carriers_xShoppingStShipping_longitude").val(place.geometry.location.lng().toFixed(5));
        });
    }
);
