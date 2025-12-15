document.addEventListener('DOMContentLoaded', () => {

    const filterType = document.getElementById('filter-type');
    const filterLocation = document.getElementById('filter-location');
    const priceMin = document.getElementById('price-min');
    const priceMax = document.getElementById('price-max');
    const priceDisplay = document.getElementById('price-display');
    const cards = document.querySelectorAll('.mapcard');
    const cardContainer = document.querySelector('.card-container');

    if (!cardContainer) return;

    let noCardMsg = document.getElementById('no-card-msg');
    if (!noCardMsg) {
        noCardMsg = document.createElement('p');
        noCardMsg.id = 'no-card-msg';
        noCardMsg.textContent = "Bravo ! Nous n'avons pas encore de cartes correspondant à votre recherche.";
        noCardMsg.style.display = 'none';
        noCardMsg.style.textAlign = 'center';
        noCardMsg.style.margin = '30px 0';
        cardContainer.appendChild(noCardMsg);
    }

    function applyFilters() {
        const typeValue = filterType.value;
        const locationValue = filterLocation.value;
        const minPrice = parseFloat(priceMin.value);
        const maxPrice = parseFloat(priceMax.value);

        let anyVisible = false;

        priceDisplay.textContent = `${minPrice.toFixed(2)}€ - ${maxPrice.toFixed(2)}€`;

        cards.forEach(card => {
            const cardType = card.dataset.type;
            const cardLocation = card.dataset.location;
            const cardPrice = parseFloat(card.dataset.price);

            let visible = true;

            if (typeValue && cardType !== typeValue) visible = false;
            if (locationValue && cardLocation !== locationValue) visible = false;
            if (cardPrice < minPrice || cardPrice > maxPrice) visible = false;

            card.style.display = visible ? 'block' : 'none';
            if (visible) anyVisible = true;
        });

        noCardMsg.style.display = anyVisible ? 'none' : 'block';
    }

    [filterType, filterLocation, priceMin, priceMax].forEach(el => {
        el.addEventListener('input', applyFilters);
        el.addEventListener('change', applyFilters);
    });

    applyFilters(); // Application initiale
});
