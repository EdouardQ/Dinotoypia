$(document).ready(function() {
    // Toggle the visibility of the widget
    var widget = $('#mondial_relais_widget')[0]; // get the div
    var shipping_input = $('#checkout_form_shipping');

    widget.style.display = 'none';

    shipping_input.change(function () {
        if (shipping_input.val() === '3') {
            widget.style.display = 'block';
        }
        else {
            widget.style.display = 'none';
        }
    })

    // Load the widget in the div "#Zone_Widget" with the following parameters
    $("#Zone_Widget").MR_ParcelShopPicker({

        Target: "#Target_Widget",
        TargetDisplay: "#TargetDisplay_Widget",
        TargetDisplayInfoPR: "#TargetDisplayInfoPR_Widget",
        // BDTEST => dev mod
        Brand: "BDTEST  ",
        Country: "FR",
        PostCode: "92130",
        // Delivery Method (Standard [24R], XL [24L], XXL [24X], Drive [DRI])
        ColLivMod: "24R",
        NbResults: "7",
        ShowResultsOnMap: true,
        DisplayMapInfo: true,
        OnParcelShopSelected:
            function(data) {
                $("#cb_id").val(data.ID);
                $("#cb_address").val(data.Adresse1);
                if (data.Adresse2 != null) {
                    $("#cb_address").val(data.Adresse1 + ' ' + data.Adresse2);
                }
                $("#cb_post_code").val(data.CP);
                $("#cb_city").val(data.Ville);
                $("#cb_country").val(data.Pays);
            },
        EnableGeolocalisatedSearch: "true",
        CSS: 1,
    });

    // Configure when the form can be submit
    var form = $("#checkout_form");
    form.submit(function (event) {
        if (shipping_input.val() == '3' && (
            $("#cb_id").val() == ""
            || $("#cb_address").val() == ""
            || $("#cb_post_code").val() == ""
            || $("#cb_city").val() == ""
            || $("#cb_country").val() == ""
        )) {
            event.preventDefault();
            $('#widgetFormError').html("Une erreur est survenue durant la sélection du point relais <br> Veuiller réessayer <br>");
        }
    })
});
