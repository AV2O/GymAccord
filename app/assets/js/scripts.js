// --- 1. CONSTANTES GLOBALES ---
const burger = document.getElementById("burgerMenu");
const menu = document.querySelector(".nav");
const photoModal = document.getElementById("photoModal");
const flashModal = document.getElementById("flashModal");
const confirmModal = document.getElementById("confirmModal");
const loginRequiredModal = document.getElementById("loginRequiredModal");
const modalText = document.getElementById("modalText");
const editBtn = document.querySelector(".edit-profile-btn");
const confirmBtn = document.getElementById("confirmBtn");

// --- 2. FONCTIONS UTILES ---
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

    // --- GESTION DES CLICS DÉLÉGUÉS (Modales et Suppressions) ---
    document.addEventListener("click", function (e) {
        const btnReserveLogin = e.target.closest("#triggerLoginModal");
        if (btnReserveLogin && loginRequiredModal) {
            e.preventDefault();
            loginRequiredModal.style.display = "block";
        }

        const btnCancel = e.target.closest(
            ".btn-cancel-session, .delete-subscription-btn",
        );
        if (btnCancel) {
            e.preventDefault();
            const url = btnCancel.getAttribute("href");
            if (confirmBtn) confirmBtn.setAttribute("href", url);

            if (modalText) {
                if (btnCancel.classList.contains("btn-cancel-session")) {
                    modalText.textContent =
                        "Voulez-vous vraiment annuler votre réservation ?";
                } else {
                    modalText.textContent =
                        "Voulez-vous vraiment résilier votre forfait ?";
                }
            }
            if (confirmModal) confirmModal.style.display = "block";
        }
    });

    // --- FERMETURE DE TOUTES LES MODALES ---
    const closeSelectors =
        ".close-modal, .btn-cancel, .close-flash-modal, .close-confirm, .btn-gym-outline";
    document.querySelectorAll(closeSelectors).forEach((btn) => {
        btn.onclick = function () {
            [photoModal, flashModal, confirmModal, loginRequiredModal].forEach(
                (m) => {
                    if (m) m.style.display = "none";
                },
            );
        };
    });

    window.onclick = function (event) {
        [photoModal, flashModal, confirmModal, loginRequiredModal].forEach(
            (m) => {
                if (event.target == m) m.style.display = "none";
            },
        );
    };

    // --- FILTRAGE DYNAMIQUE DU CALENDRIER ---
    const dayFilters = document.querySelectorAll(".filter-day");
    const workshopCards = document.querySelectorAll(".workshop-card");
    const emptyMessage = document.getElementById("empty-day-message");
    const emptyText = document.getElementById("dynamic-empty-text");

    if (dayFilters.length > 0) {
        dayFilters.forEach((filter) => {
            filter.addEventListener("click", function (e) {
                e.preventDefault();
                dayFilters.forEach((f) =>
                    f.parentElement.classList.remove("active"),
                );
                this.parentElement.classList.add("active");

                const selectedDay = this.getAttribute("data-day");
                let hasWorkshops = false;

                workshopCards.forEach((card) => {
                    if (card.getAttribute("data-day") === selectedDay) {
                        card.classList.remove("hidden");
                        hasWorkshops = true;
                    } else {
                        card.classList.add("hidden");
                    }
                });

                if (!hasWorkshops) {
                    if (emptyText) {
                        emptyText.textContent =
                            selectedDay === "Dimanche"
                                ? "C'est le repos du guerrier pour nos profs, mais la salle reste à votre disposition !"
                                : "Aucun cours prévu pour ce jour-là.";
                    }
                    if (emptyMessage) emptyMessage.style.display = "block";
                } else {
                    if (emptyMessage) emptyMessage.style.display = "none";
                }
            });
        });
        dayFilters[0].click();
    }

    // --- FILTRES DE LA PAGE RÉSERVATION ---
    const selectCategorie = document.getElementById("categorie");
    const selectSport = document.getElementById("choix-sport");
    const selectDate = document.getElementById("cours");
    const boutonSubmit = document.getElementById("submit-btn");

    if (selectCategorie && typeof allWorkshops !== "undefined") {
        selectCategorie.addEventListener("change", function () {
            const typeIdChoisi = this.value;
            selectSport.innerHTML =
                '<option value="" selected disabled>Choisir une discipline...</option>';
            selectDate.innerHTML =
                '<option value="" selected disabled>Date et heure</option>';
            selectDate.disabled = true;
            if (boutonSubmit) boutonSubmit.disabled = true;

            const filtered = allWorkshops.filter(
                (w) => w.typeId == typeIdChoisi,
            );
            const nomsUniques = [...new Set(filtered.map((w) => w.name))];

            nomsUniques.forEach((nom) => {
                const opt = document.createElement("option");
                opt.value = nom;
                opt.textContent = nom;
                selectSport.appendChild(opt);
            });
            selectSport.disabled = false;
        });
    }

    if (selectSport && typeof allWorkshops !== "undefined") {
        selectSport.addEventListener("change", function () {
            const nomSportChoisi = this.value;
            selectDate.innerHTML =
                '<option value="" selected disabled>Date et heure</option>';

            const creneaux = allWorkshops.filter(
                (w) => w.name == nomSportChoisi,
            );
            creneaux.forEach((w) => {
                const opt = document.createElement("option");
                opt.value = w.id;
                opt.textContent = w.label;
                selectDate.appendChild(opt);
            });
            selectDate.disabled = false;
        });
    }

    if (selectDate) {
        selectDate.addEventListener("change", function () {
            if (this.value && boutonSubmit) boutonSubmit.disabled = false;
        });
    }

    // --- NOUVEAU : VALIDATION FORMULAIRE CONTACT ---
    const contactForm = document.getElementById("contact-form");
    if (contactForm) {
        contactForm.addEventListener("submit", function (e) {
            let isValid = true;

            // On récupère les champs et zones d'erreurs

            const email = document.getElementById("email");
            const message = document.getElementById("message");
            const consentement = document.getElementById("consentement");

            // Reset des messages d'erreurs
            document
                .querySelectorAll(".error-message")
                .forEach((el) => (el.textContent = ""));

            // Validation Email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email && !emailRegex.test(email.value)) {
                document.getElementById("error-email").textContent =
                    "Veuillez entrer un email valide.";
                isValid = false;
            }

            // Validation Message
            if (message && message.value.trim().length < 10) {
                document.getElementById("error-message").textContent =
                    "Votre message doit faire au moins 10 caractères.";
                isValid = false;
            }

            // Validation Consentement
            if (consentement && !consentement.checked) {
                document.getElementById("error-consentement").textContent =
                    "Vous devez accepter la politique de confidentialité.";
                isValid = false;
            }

            // Si le formulaire n'est pas valide, on empêche l'envoi
            if (!isValid) {
                e.preventDefault();
            }
        });
    }

    // Affichage des messages flash au chargement
    if (flashModal) flashModal.style.display = "block";
});
