let cookie = getCookie("order");
let cartDiv = document.getElementById("cart-quantity");
if (cookie.length === 2) {
    cartDiv.style.right = "1em";
} else if (cookie.length === 3) {
    cartDiv.style.right = "0.7em";
}

cartDiv.innerHTML = cookie;

function getCookie(cookieName) {
    let cookie = {};
    document.cookie.split(';').forEach(function(el) {
        let [key,value] = el.split('=');
        cookie[key.trim()] = value;
    })
    return cookie[cookieName];
}