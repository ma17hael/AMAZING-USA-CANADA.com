document.addEventListener("DOMContentLoaded", () => {
    const langBtn = document.getElementById("langBtn");
    const langMenu = document.getElementById("langMenu");
    const currentFlag = document.getElementById("current-flag");
    let currentLang = typeof currentLangFromPHP !== "undefined" ? currentLangFromPHP : "fr";

    // Met à jour le drapeau affiché au chargement
    const activeLangItem = langMenu.querySelector(`[data-lang="${currentLang}"] img`);
    if (activeLangItem) currentFlag.src = activeLangItem.src;

    langBtn.addEventListener("click", () => {
        langMenu.classList.toggle("hidden");
    });
    langMenu.querySelectorAll("li").forEach(item => {
        item.addEventListener("click", () => {
            currentLang = item.dataset.lang;
            currentFlag.src = item.querySelector("img").src;
            langMenu.classList.add("hidden");
            //Recharger la page avec la langue choisi
            const url = new URL(window.location.href);
            url.searchParams.set('lang', currentLang);
            window.location.href = url.toString();
        });
    });
    //Fermer le menu si clic extérieur
    document.addEventListener("click", (e) => {
        if (!langBtn.contains(e.target) && !langMenu.contains(e.target)) {
            langMenu.classList.add("hidden");
        }
    });
});