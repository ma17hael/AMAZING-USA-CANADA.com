document.addEventListener('DOMContentLoaded', () => {
    const megaBtn  = document.querySelector('.js-mega-btn');
    const megaMenu = document.getElementById('megaMenu');
    const userBtn  = document.querySelector('.js-user-btn');
    const userDrop = document.getElementById('userDropdown');

    function closeAll() {
        megaMenu?.classList.remove('open');
        megaBtn?.classList.remove('open');
        megaBtn?.setAttribute('aria-expanded', 'false');
        userDrop?.classList.remove('open');
        userBtn?.classList.remove('open');
        userBtn?.setAttribute('aria-expanded', 'false');
    }

    megaBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        const isOpen = megaMenu.classList.toggle('open');
        megaBtn.classList.toggle('open', isOpen);
        megaBtn.setAttribute('aria-expanded', isOpen);
        if (isOpen) {
            userDrop?.classList.remove('open');
            userBtn?.classList.remove('open');
        }
    });

    userBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        const isOpen = userDrop.classList.toggle('open');
        userBtn.classList.toggle('open', isOpen);
        userBtn.setAttribute('aria-expanded', isOpen);
        if (isOpen) {
            megaMenu?.classList.remove('open');
            megaBtn?.classList.remove('open');
        }
    });

    // Ferme au clic extérieur
    document.addEventListener('click', closeAll);

    // Ferme à Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeAll();
    });

    // Navigation clavier dans le dropdown
    userBtn?.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            userBtn.click();
        }
    });
});