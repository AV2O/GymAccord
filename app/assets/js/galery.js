// On récupère le conteneur qui contient toutes les photos
const galery = document.querySelector('.galery');

let xDepart = null;

// 1. Détection du début du toucher
galery.addEventListener('touchstart', (e) => {
    // On enregistre la position horizontale du doigt au moment du contact
    xDepart = e.touches[0].clientX;
}, { passive: true });

// 2. Détection du mouvement du doigt
galery.addEventListener('touchmove', (e) => {
    // Si on n'a pas de point de départ, on ne fait rien
    if (!xDepart) return;

    let xFin = e.touches[0].clientX;
    let difference = xDepart - xFin;

    // Seuil de détection : il faut bouger de plus de 50px pour déclencher le slide
    if (Math.abs(difference) > 50) {
        
        if (difference > 0) {
            /* --- SWIPE VERS LA GAUCHE (Suivant) --- */
            // On prend la première photo (la plus à gauche/derrière)
            const firstPhoto = galery.firstElementChild;
            // On la déplace à la toute fin du conteneur
            // Elle devient alors le nouveau :last-child (le focus au centre)
            galery.appendChild(firstPhoto);
        } 
        else {
            /* --- SWIPE VERS LA DROITE (Précédent) --- */
            // On prend la photo centrale actuelle (:last-child)
            const lastPhoto = galery.lastElementChild;
            // On la remet au tout début du conteneur
            // L'image qui était juste avant (:nth-last-child(2)) devient le nouveau focus
            galery.prepend(lastPhoto);
        }

        // On réinitialise xDepart à null pour ne pas déclencher 
        // plusieurs slides d'un seul coup
        xDepart = null;
    }
}, { passive: true });