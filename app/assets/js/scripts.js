// --- 1. CONSTANTES GLOBALES ---
const burger = document.getElementById("burgerMenu");
const menu = document.querySelector(".nav");
const photoModal = document.getElementById("photoModal");
const flashModal = document.getElementById("flashModal");
const confirmModal = document.getElementById("confirmModal");
const modalText = document.getElementById("modalText"); 
const editBtn = document.querySelector(".edit-profile-btn");
const confirmBtn = document.getElementById("confirmBtn");

// --- 2. FONCTION MENU BURGER ---
function initMenuBurger() {
    if (burger && menu) {
        burger.addEventListener("click", function () {
            burger.classList.toggle("active");
            menu.classList.toggle("menu-open");
        });
    }
}

// --- 3. INITIALISATION GÉNÉRALE ---
document.addEventListener("DOMContentLoaded", function () {
    
    // Lancement du menu burger
    initMenuBurger();

    // --- GESTION PHOTO (Profil) ---
    if (editBtn && photoModal) {
        editBtn.onclick = function (e) {
            e.preventDefault();
            photoModal.style.display = "block";
        };
    }

    // --- GESTION CONFIRMATION SUPPRESSION (Séances et Forfait) ---
    document.addEventListener("click", function (e) {
        // Détecte le bouton de suppression de séance ou de forfait
        const btn = e.target.closest(".btn-cancel-session, .delete-subscription-btn");

        if (btn) {
            e.preventDefault();
            
            const url = btn.getAttribute("href");
            if (confirmBtn) confirmBtn.setAttribute("href", url);

            // Mise à jour du message selon le bouton cliqué
            if (modalText) {
                if (btn.classList.contains("btn-cancel-session")) {
                    modalText.textContent = "Voulez-vous vraiment annuler votre réservation pour ce cours ?";
                } else {
                    modalText.textContent = "Voulez-vous vraiment résilier votre forfait ?";
                }
            }

            if (confirmModal) confirmModal.style.display = "block";
        }
    });

    // --- GESTION DES MESSAGES FLASH (Automatique) ---
    if (flashModal) {
        flashModal.style.display = "block";
    }

    // --- FERMETURE DE TOUTES LES MODALES ---
    const closeButtons = ".close-modal, .btn-cancel, .close-flash-modal, .close-confirm, .btn-gym";
    document.querySelectorAll(closeButtons).forEach((btn) => {
        btn.onclick = function () {
            if (photoModal) photoModal.style.display = "none";
            if (flashModal) flashModal.style.display = "none";
            if (confirmModal) confirmModal.style.display = "none";
        };
    });

    // Fermeture au clic à l'extérieur (fond sombre)
    window.onclick = function (event) {
        if (event.target == photoModal) photoModal.style.display = "none";
        if (event.target == flashModal) flashModal.style.display = "none";
        if (event.target == confirmModal) confirmModal.style.display = "none";
    };

    // --- FILTRES LISTE DES COURS (Réservation) ---
    const selectCategorie = document.getElementById("categorie");
    const selectSport = document.getElementById("choix-sport");
    const selectDate = document.getElementById("cours");
    const boutonSubmit = document.getElementById("submit-btn");

    // ÉTAPE 1 : Choix de la Catégorie
    if (selectCategorie) {
        selectCategorie.addEventListener("change", function () {
            const typeIdChoisi = this.value;
            selectSport.innerHTML = '<option value="" selected disabled>Choisir une discipline...</option>';
            selectDate.innerHTML = '<option value="" selected disabled>Date et heure</option>';
            selectDate.disabled = true;
            if(boutonSubmit) boutonSubmit.disabled = true;

            // "allWorkshops" doit être défini dans ton Twig via JSON
            const workshopsFiltresParType = allWorkshops.filter(w => w.typeId == typeIdChoisi);
            const nomsUniques = [...new Set(workshopsFiltresParType.map(w => w.name))];

            nomsUniques.forEach((nom) => {
                const option = document.createElement("option");
                option.value = nom;
                option.textContent = nom;
                selectSport.appendChild(option);
            });
            selectSport.disabled = false;
        });
    }

    // ÉTAPE 2 : Choix du Sport
    if (selectSport) {
        selectSport.addEventListener("change", function () {
            const nomSportChoisi = this.value;
            selectDate.innerHTML = '<option value="" selected disabled>Date et heure</option>';
            if(boutonSubmit) boutonSubmit.disabled = true;

            const creneauxDisponibles = allWorkshops.filter(w => w.name == nomSportChoisi);
            creneauxDisponibles.forEach((w) => {
                const option = document.createElement("option");
                option.value = w.id;
                option.textContent = w.label;
                selectDate.appendChild(option);
            });
            selectDate.disabled = false;
        });
    }

    // ÉTAPE 3 : Choix de la date finale
    if (selectDate) {
        selectDate.addEventListener("change", function () {
            if (this.value && boutonSubmit) {
                boutonSubmit.disabled = false;
            }
        });
    }
});