document.addEventListener('DOMContentLoaded', () => {

    const minSlider = document.getElementById('price-min');
    const maxSlider = document.getElementById('price-max');
    const display = document.getElementById('price-display');

    if (!minSlider || !maxSlider || !display) {
        console.error('Erreur prix : éléments manquants dans le DOM');
        return;
    }
    const currency = display.dataset.currency || '';
    function updatePriceDisplay() {
        // Utiliser parseFloat pour conserver les décimales
        let min = parseFloat(minSlider.value);
        let max = parseFloat(maxSlider.value);

        // Empêche le croisement incohérent
        if (min > max) {
            [min, max] = [max, min];
            minSlider.value = min;
            maxSlider.value = max;
        }

        // Affichage avec deux décimales
        display.textContent = `${min.toFixed(2)}${currency} - ${max.toFixed(2)}${currency}`;
    }

    minSlider.addEventListener('input', updatePriceDisplay);
    maxSlider.addEventListener('input', updatePriceDisplay);

    updatePriceDisplay(); // affichage initial
});
