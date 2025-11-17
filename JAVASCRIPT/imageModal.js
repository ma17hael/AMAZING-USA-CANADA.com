document.querySelectorAll('.mapcard img').forEach(img => {
    img.addEventListener('click', () => {
        const modal = document.getElementById('image-modal');
        const modalImg = document.getElementById('modal-img');

        modal.style.display = 'block';
        modalImg.src = img.src;
    });
});

// Fermeture
document.querySelector('.modal .close').addEventListener('click', () => {
    document.getElementById('image-modal').style.display = 'none';
});

// Fermeture en cliquant hors de l’image
document.getElementById('image-modal').addEventListener('click', (e) => {
    if (e.target.id === 'image-modal') {
        e.target.style.display = 'none';
    }
});