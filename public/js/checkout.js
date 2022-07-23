function initDisplayShipping(data) {
    var displayDeliveryPrice = $('#displayDeliveryPrice');
    var shipping_input = $('#checkout_form_shipping');

    displayDeliveryPrice.html(data[0]['fee'] + ' €')

    shipping_input.change(function () {
        displayDeliveryPrice.html(data[shipping_input.val() - 1]['fee'] + ' €')
        calcTotal();
    })
}

jQuery(document).ready(function() {
    var request = null;
    var promo_code = $("#checkout_form_promotion_code");
    var promocodeInputBtn = $('#promocode_input_btn');
    var promotionMessage = $('#promotion_message');

    promocodeInputBtn.click(function() {
        var value = promo_code.val().toUpperCase();
        if (request != null)
            request.abort();
        request = $.ajax({
            type: "GET",
            url: "/payment/checkout/promocode/"+value,
            success: function (msg) {
                if (value === $(promo_code).val().toUpperCase()) {
                    var response = JSON.parse(msg);
                    if (response.code == "null") {
                        promotionMessage.html(response.message).addClass('alert-success');
                    }
                    setTimeout(() => {
                        promotionMessage.html("<br>");
                        promotionMessage.removeClass('alert-success');
                    }, 5000);

                    /*
                    if (response.code == "null") {
                        promotionMessage.html(response.message).addClass('alert-success');
                    }
                    else if (response.code == "removed") {
                        promotionMessage.html(response.message).addClass('alert-success');
                    }
                    */
                }
            }
        })
    });

});


function calcTotal() {

}
