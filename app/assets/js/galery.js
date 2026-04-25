// On récupère le conteneur qui contient toutes les photos
const galery = document.querySelector('.galery');

// --- LE "IF" DE SÉCURITÉ ---
// Si l'élément '.galery' n'existe pas sur cette page (ex: page réservation), 
// on arrête le script ici et on évite l'erreur.
if (galery) {

    let xDepart = null;

    // 1. Détection du début du toucher
    galery.addEventListener('touchstart', (e) => {
        xDepart = e.touches[0].clientX;
    }, { passive: true });

    // 2. Détection du mouvement du doigt
    galery.addEventListener('touchmove', (e) => {
        if (!xDepart) return;

        let xFin = e.touches[0].clientX;
        let difference = xDepart - xFin;

        if (Math.abs(difference) > 50) {
            
            if (difference > 0) {
                /* --- SWIPE VERS LA GAUCHE --- */
                const firstPhoto = galery.firstElementChild;
                galery.appendChild(firstPhoto);
            } 
            else {
                /* --- SWIPE VERS LA DROITE --- */
                const lastPhoto = galery.lastElementChild;
                galery.prepend(lastPhoto);
            }

            xDepart = null;
        }
    }, { passive: true });

} 