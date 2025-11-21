const minSlider = document.getElementById('price-min');
const maxSlider = document.getElementById('price-max');
const display = document.getElementById('price-display');

function updatePriceDisplay() {
    let min = parseInt(minSlider.value);
    let max = parseInt(maxSlider.value);
    if(min > max) { // éviter que min dépasse max
        [min, max] = [max, min];
    }
    display.textContent = `${min}€ - ${max}€`;
}

minSlider.addEventListener('input', updatePriceDisplay);
maxSlider.addEventListener('input', updatePriceDisplay);

updatePriceDisplay(); // affichage initial
