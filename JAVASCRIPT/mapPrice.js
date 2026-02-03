document.addEventListener('DOMContentLoaded', () => {

    const filterType = document.getElementById('filter-type');
    const filterLocation = document.getElementById('filter-location');
    const priceMin = document.getElementById('price-min');
    const priceMax = document.getElementById('price-max');
    const priceDisplay = document.getElementById('price-display');
    const cards = document.querySelectorAll('.mapcard');
    const cardContainer = document.querySelector('.card-container');

    if (!cardContainer) return;

    // Message "aucune carte"
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

    const currency = priceDisplay.dataset.currency;
    const rate = parseFloat(priceDisplay.dataset.rate);
    const formatter = new Intl.NumberFormat(undefined, {
        style: 'currency',
        currency: currency
    });

    function updatePriceDisplay() {
        let minEuro = parseFloat(priceMin.value);
        let maxEuro = parseFloat(priceMax.value);

        // Empêche le croisement
        if (minEuro > maxEuro) {
            [minEuro, maxEuro] = [maxEuro, minEuro];
            priceMin.value = minEuro;
            priceMax.value = maxEuro;
        }

        // Conversion pour affichage
        const minConverted = minEuro * rate;
        const maxConverted = maxEuro * rate;

        priceDisplay.textContent = `${formatter.format(minConverted)} – ${formatter.format(maxConverted)}`;

        return { minEuro, maxEuro };
    }

    function applyFilters() {
        const { minEuro, maxEuro } = updatePriceDisplay();
        const typeValue = filterType.value;
        const locationValue = filterLocation.value;

        let anyVisible = false;

        cards.forEach(card => {
            const cardType = card.dataset.type;
            const cardLocation = card.dataset.location;
            const cardPrice = parseFloat(card.dataset.price);

            let visible = true;

            if (typeValue && cardType !== typeValue) visible = false;
            if (locationValue && cardLocation !== locationValue) visible = false;
            if (cardPrice < minEuro || cardPrice > maxEuro) visible = false;

            card.style.display = visible ? 'block' : 'none';
            if (visible) anyVisible = true;
        });

        noCardMsg.style.display = anyVisible ? 'none' : 'block';
    }

    // Initialisation
    priceMin.value = 0;
    priceMax.value = parseFloat(priceDisplay.dataset.max);
    applyFilters();

    [filterType, filterLocation, priceMin, priceMax].forEach(el => {
        el.addEventListener('input', applyFilters);
        el.addEventListener('change', applyFilters);
    });

});
