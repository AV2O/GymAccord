const galery = document.querySelector('.galery');

if (galery) {
    let xDepart = null;

    // --- 1. MOBILE ---
    galery.addEventListener('touchstart', (e) => {
        xDepart = e.touches[0].clientX;
    }, { passive: true });

    galery.addEventListener('touchmove', (e) => {
        if (!xDepart) return;
        let xFin = e.touches[0].clientX;
        let difference = xDepart - xFin;

        if (Math.abs(difference) > 50) {
            if (difference > 0) {
                const firstPhoto = galery.firstElementChild;
                galery.appendChild(firstPhoto);
            } else {
                const lastPhoto = galery.lastElementChild;
                galery.prepend(lastPhoto);
            }
            xDepart = null;
        }
    }, { passive: true });


    // --- 2. DESKTOP ---
    galery.addEventListener('mousedown', (e) => {
        // On calcule si on a cliqué sur la moitié gauche ou droite de la galerie
        const rect = galery.getBoundingClientRect();
        const xClic = e.clientX - rect.left; // Position du clic dans la galerie

        if (xClic > rect.width / 2) {
            /* CLIC À DROITE : On avance (la première photo part à la fin) */
            const firstPhoto = galery.firstElementChild;
            galery.appendChild(firstPhoto);
        } else {
            /* CLIC À GAUCHE : On recule (la dernière photo vient au début) */
            const lastPhoto = galery.lastElementChild;
            galery.prepend(lastPhoto);
        }
    });

    // Optionnel : Empêcher le "drag" par défaut des images qui bloque le clic
    galery.querySelectorAll('img').forEach(img => {
        img.setAttribute('draggable', false);
    });
}