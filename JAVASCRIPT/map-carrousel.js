const track = document.querySelector('.carrousel-track');
const cards = document.querySelectorAll('.mapcard');

const cardWidth = cards[0].getBoundingClientRect().width + 20; // largeur + marges
let index = 0;

// Dupliquer les cartes pour l'infini
const total = cards.length;

for (let i = 0; i < total; i++) {
    const clone = cards[i].cloneNode(true);
    track.appendChild(clone);
}

for (let i = total - 1; i >= 0; i--) {
    const clone = cards[i].cloneNode(true);
    track.insertBefore(clone, track.firstChild);
}

// Position initiale au milieu
index = total;
track.style.transform = `translateX(${-index * cardWidth}px)`;

// Boutons
document.querySelector('.next').addEventListener('click', () => {
    index++;
    track.style.transition = "transform 0.5s ease";
    track.style.transform = `translateX(${-index * cardWidth}px)`;

    if (index >= total * 2) {
        setTimeout(() => {
            track.style.transition = "none";
            index = total;
            track.style.transform = `translateX(${-index * cardWidth}px)`;
        }, 500);
    }
});

document.querySelector('.prev').addEventListener('click', () => {
    index--;
    track.style.transition = "transform 0.5s ease";
    track.style.transform = `translateX(${-index * cardWidth}px)`;

    if (index < total) {
        setTimeout(() => {
            track.style.transition = "none";
            index = total * 2 - 1;
            track.style.transform = `translateX(${-index * cardWidth}px)`;
        }, 500);
    }
});