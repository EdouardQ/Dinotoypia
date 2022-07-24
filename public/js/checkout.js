var pre_total;
var shipping_data;
var shipping_fee;
var promotion_type;
var promotion_amount;

function initCheckout(pre_total_input, shipping_data_input) {
    pre_total = parseFloat(pre_total_input);
    shipping_data = shipping_data_input;
    shipping_fee = parseFloat(shipping_data[0]['fee']);
    $('#displayDeliveryPrice').html(shipping_fee.toFixed(2) + ' €');
    udatePromotion();
    calcTotal();
}

$('#checkout_form_shipping').change(function () {
    shipping_fee = parseFloat(shipping_data[$('#checkout_form_shipping').val() - 1]['fee']);
    $('#displayDeliveryPrice').html(shipping_fee.toFixed(2) + ' €');
    calcTotal();
})

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
                    // display alert
                    if (response.code == "add" || response.code == "removed" || response.code == "aleardy in order") {
                        promotionMessage.html(response.message).addClass('alert-success');
                    }
                    else if (response.code == "not found" || response.code == "condition not meet" /* || response.code == "null" */) {
                        promotionMessage.html(response.message).addClass('alert-danger');
                    }
                    setTimeout(() => {
                        promotionMessage.html("<br>");
                        promotionMessage.removeClass('alert-success');
                        promotionMessage.removeClass('alert-danger');
                    }, 5000);

                    // add promotion code to recap
                    if (response.code == "add") {
                        if (response.type == "percentage") {
                            $("#displayPromotionReduction").html('- ' + parseFloat(response.amount).toFixed(0) + '%');
                        }
                        else {
                            $("#displayPromotionReduction").html('- ' + parseFloat(response.amount).toFixed(2) + ' €');
                        }
                        udatePromotion();
                        calcTotal();
                    }
                    else if (response.code == "removed") {
                        $("#displayPromotionReduction").html('-');
                        udatePromotion();
                        calcTotal();
                    }
                }
            }
        })
    });
});

function udatePromotion() {
    var promotionReductionHtml = $("#displayPromotionReduction").html().replace(/\s/g, ''); // remove all spaces

    if (promotionReductionHtml == '-') {
        promotion_type = null;
        promotion_amount = null;
    }
    else {
        if (promotionReductionHtml.slice(-1) == '%') {
            promotion_type = 'percentage';
        }
        else {
            promotion_type = 'amount';
        }
        promotion_amount = parseFloat(promotionReductionHtml.slice(1, -1));
    }
}

function calcTotal() {
    var total = pre_total;
    if (promotion_type != null && promotion_amount != null) {
        if (promotion_type == 'percentage') {
            total = total*(1-promotion_amount/100);
        }
        else if (promotion_type == 'amount') {
            total -= promotion_amount;
        }
    }
    total += shipping_fee;
    $('#displayTotalPrice').html(total.toFixed(2) + ' €');
}
