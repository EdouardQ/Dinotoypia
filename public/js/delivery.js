let selector = document.getElementById('delivery_type');
let mondialRelay = document.getElementById('mondial_relay');
let poste = document.getElementById('poste');

// init div
mondialRelay.style.display = "none";

selector.addEventListener('change', function () {
    // 1 => Livraison Colissimo || 2 => Livraison Chronopost
    if (selector.value === "1" || selector.value === "2") {
        poste.style.display = "block";
        mondialRelay.style.display = "none";
    }
    // 3 => Livraison Mondial Relais
    else if (selector.value === "3") {
        poste.style.display = "none";
        mondialRelay.style.display = "block";
    }
})