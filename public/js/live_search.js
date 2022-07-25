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
                                entitySelector.append("<li><a href='/product/"+urlName+"'><img class='searchbar_products' src='/img/products/"+image+"' alt=''></img>"+name+"</a></li>");
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
});