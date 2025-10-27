document.addEventListener("DOMContentLoaded", () => {
    const logo3D = document.querySelector(".logo-3d");
    if (!logo3D) return;

    let flipped = false;
    const toggleLogo = () =>  {
        flipped = !flipped;
        logo3D.style.transition = "transform 1s ease-in-out";
        logo3D.style.transform = `rotateY(${flipped ? 180 : 0}deg)`;
    };
    const isMobile = /Mobi|Android|iPhone|iPad/i.test(navigator.userAgent);
    if (isMobile) {
        //Sur mobile -> changement automatique toutes les 5 secondes
        setInterval(toggleLogo, 5000);
    } else {
        //Sur ordinateur -> Changement au survol
        logo3D.parentElement.addEventListener("mouseenter", toggleLogo);
    }
});