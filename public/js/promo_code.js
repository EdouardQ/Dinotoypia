jQuery(document).ready(function() {
    var request = null;
    $('#promo_code_form').submit(function (event) {
        event.preventDefault();

        var promo_code = $("#promo_code");
        var value = promo_code.val().toUpperCase();

        if (value.length > 0) {
            if (request != null)
                request.abort();
            request = $.ajax({
                type: "GET",
                url: "/checkout/add/promocode/"+value,
                /*
                success: function (msg) {
                    if (value === $(promo_code).val().toUpperCase()) {
                        var result = JSON.parse(msg);
                        console.log(result);
                    }
                }
                */
            })
        }
    })
});
