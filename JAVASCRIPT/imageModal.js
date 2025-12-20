document.addEventListener('click', (e) => {

    // OUVERTURE
    const img = e.target.closest('[data-modal-image]');
    if (img) {
        const modal = document.getElementById('image-modal');
        const modalImg = document.getElementById('modal-img');

        modal.style.display = 'flex';
        modalImg.src = img.src;
        return;
    }

    // FERMETURE bouton
    if (e.target.classList.contains('close')) {
        document.getElementById('image-modal').style.display = 'none';
        return;
    }

    // FERMETURE clic extérieur
    const modal = document.getElementById('image-modal');
    if (e.target === modal) {
        modal.style.display = 'none';
    }
});
