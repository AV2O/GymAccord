document.addEventListener("DOMContentLoaded", function () {
    // --- 1. ÉLÉMENTS ---
    const burger = document.getElementById("burgerMenu");
    const menu = document.querySelector(".nav");
    const selectCategorie = document.getElementById("categorie");
    const selectSport = document.getElementById("choix-sport");
    const selectDate = document.getElementById("cours");
    const boutonSubmit = document.getElementById("submit-btn");
    const sectionFiltre = document.querySelector(".form-contact");

    // Modales
    const flashModal = document.getElementById("flashModal");
    const confirmModal = document.getElementById("confirmModal");
    const loginRequiredModal = document.getElementById("loginRequiredModal");
    const photoModal = document.getElementById("photoModal");

    function getWorkshopsData() {
        if (typeof allWorkshops !== "undefined") return allWorkshops;
        const raw = sectionFiltre
            ? sectionFiltre.getAttribute("data-workshops")
            : "[]";
        return JSON.parse(raw);
    }

    // --- 2. BURGER ---
    if (burger && menu) {
        burger.onclick = () => {
            burger.classList.toggle("active");
            menu.classList.toggle("menu-open");
        };
    }

    // --- 3. MODALES ---

    // ✅ Flash modale (succès / erreur après action)
    if (flashModal && flashModal.querySelector(".modal-content")) {
        flashModal.style.display = "block";
    }

    // ✅ Modale photo profil
    const openModalBtn = document.getElementById("openModal");
    if (openModalBtn && photoModal) {
        openModalBtn.onclick = (e) => {
            e.preventDefault();
            photoModal.style.display = "block";
        };
    }

    // ✅ Modale de confirmation (annulation séance + résiliation forfait)
    document.querySelectorAll(".btn-confirm-action").forEach((btn) => {
        btn.onclick = (e) => {
            e.preventDefault();
            const url = btn.getAttribute("data-confirm-url");
            const message = btn.getAttribute("data-confirm-message");

            document.getElementById("modalText").textContent = message;
            document.getElementById("confirmBtn").href = url;

            if (confirmModal) confirmModal.style.display = "block";
        };
    });

    // ✅ Modale connexion requise (bouton réserver sans être connecté)
    document.querySelectorAll(".btn-trigger-login").forEach((btn) => {
        btn.onclick = (e) => {
            e.preventDefault();
            if (loginRequiredModal) loginRequiredModal.style.display = "block";
        };
    });

    // Fermeture de toutes les modales
    document
        .querySelectorAll(
            ".close-modal, .btn-cancel, .close-flash-modal, .close-confirm",
        )
        .forEach((btn) => {
            btn.onclick = () => {
                document
                    .querySelectorAll(".modal")
                    .forEach((m) => (m.style.display = "none"));
            };
        });

    // --- 4. LOGIQUE DE RÉSERVATION ---
    if (selectCategorie) {
        selectCategorie.addEventListener("change", function () {
            const workshops = getWorkshopsData();
            selectSport.innerHTML =
                '<option value="" selected disabled>Choisir une discipline...</option>';
            selectDate.innerHTML =
                '<option value="" selected disabled>Date et heure</option>';
            selectDate.disabled = true;

            const filtered = workshops.filter(
                (w) => String(w.typeId) === String(this.value),
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

    if (selectSport) {
        selectSport.addEventListener("change", function () {
            const workshops = getWorkshopsData();
            const nomSportSelectionne = this.value;

            selectDate.innerHTML =
                '<option value="" selected disabled>Date et heure</option>';
            const creneaux = workshops.filter(
                (w) => w.name === nomSportSelectionne,
            );

            if (creneaux.length > 0) {
                creneaux.forEach((w) => {
                    const opt = document.createElement("option");
                    opt.value = w.id;
                    opt.textContent = w.label;
                    selectDate.appendChild(opt);
                });
                selectDate.disabled = false;
            }
        });
    }

    if (selectDate) {
        selectDate.addEventListener("change", () => {
            if (boutonSubmit) boutonSubmit.disabled = false;
        });
    }

    // --- 5. AUTO-SÉLECTION ---

    // Cas A : pré-sélection workshop complet (existant)
    if (sectionFiltre && selectCategorie && selectSport) {
        const idASelectionner = sectionFiltre.getAttribute("data-selected-id");
        if (idASelectionner && idASelectionner !== "null") {
            const workshops = getWorkshopsData();
            const monCours = workshops.find(
                (w) => String(w.id) === String(idASelectionner),
            );

            if (monCours) {
                selectCategorie.value = monCours.typeId;
                selectCategorie.dispatchEvent(new Event("change"));

                setTimeout(() => {
                    selectSport.value = monCours.name;
                    selectSport.dispatchEvent(new Event("change"));

                    setTimeout(() => {
                        selectDate.value = idASelectionner;
                        selectDate.dispatchEvent(new Event("change"));
                    }, 150);
                }, 150);
            }
        }
    }

    // ✅ Cas B : pré-sélection catégorie uniquement (page solo)
    if (sectionFiltre && selectCategorie) {
        const typeIdASelectionner = sectionFiltre.getAttribute(
            "data-selected-type-id",
        );
        if (typeIdASelectionner && typeIdASelectionner !== "") {
            selectCategorie.value = typeIdASelectionner;
            selectCategorie.dispatchEvent(new Event("change"));
            // L'utilisateur choisit lui-même le cours et le créneau
        }
    }

    // --- 6. PLANNING ---
    const dayFilters = document.querySelectorAll(".filter-day");
    dayFilters.forEach((f) => {
        f.onclick = (e) => {
            e.preventDefault();
            dayFilters.forEach((el) =>
                el.parentElement.classList.remove("active"),
            );
            f.parentElement.classList.add("active");
            const day = f.getAttribute("data-day");
            document.querySelectorAll(".workshop-card").forEach((c) => {
                c.classList.toggle(
                    "hidden",
                    c.getAttribute("data-day") !== day,
                );
            });
        };
    });
    
    // ✅ Pré-sélection de Lundi au chargement
    const lundiFilter = document.querySelector(".filter-day[data-day='Lundi']");
    if (lundiFilter) lundiFilter.click();
});
