const cart = document.getElementById("cart-quantity");
const n = cart.innerText;
if (n >= 10) {
    cart.style.right = "1em";
} else if (n >= 100) {
    cart.style.right = "0.7em";
}
