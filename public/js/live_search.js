jQuery(document).ready(function() {
    var searchRequest = null;

    $("#searchBar").keyup(function() {
        var minlength = 3;
        var that = this;
        var value = $(this).val();
        var entitySelector = $("#searchResults").html('');
        if (value.length >= minlength ) {
            if (searchRequest != null)
                searchRequest.abort();
            searchRequest = $.ajax({
                type: "GET",
                url: "/search/"+value,
                success: function(msg){
                    //we need to check if the value is the same
                    if (value==$(that).val()) {
                        var result = JSON.parse(msg);
                        if (result.error) {
                            entitySelector.append("<li>"+result.error+"</li>");
                        }
                        else {
                            $.each(result, function(key, arr) {
                                const name = arr.name;
                                const urlName = arr.urlName;
                                const image = arr.image;
                                const price = arr.price;
                                entitySelector.append("<li>" +
                                    "<a href='/product/"+urlName+"'>" +
                                        "<img class='img_product' src='/img/products/"+image+"' alt=''></img>" +
                                        "<span class='name_product'>"+name+"</span>"+
                                        "<span class='price_product'>"+price+" €</span>"+
                                    "</a></li>");
                            });
                        }
                    }
                }
            });
        }
        else {
            // clean results
            entitySelector.html('');
        }
    });

    document.getElementById("searchBar").addEventListener("search", function(event) {
        $("#searchResults").html('');
    });
});