window.addEventListener("scroll", function () {
    const header = document.querySelector(".site-header");

    if (window.scrollY > 10) {
        header.classList.add("scrolled");
    } else {
        header.classList.remove("scrolled");
    }
});

function toggleLang() {
    document.getElementById('langList').classList.toggle('show');
}

// fermeture si clic extérieur
document.addEventListener('click', function(e) {
    const dropdown = document.querySelector('.lang-dropdown');
    if (!dropdown.contains(e.target)) {
        document.getElementById('langList').classList.remove('show');
    }
});