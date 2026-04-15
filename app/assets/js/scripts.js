function initMenuBurger() {
    // Trouve les boutons
    const burger = document.getElementById("burgerMenu");
    const menu = document.querySelector(".nav");

    // Toggle au clic
    burger.addEventListener("click", function () {
        burger.classList.toggle("active");
        menu.classList.toggle("menu-open");
    });
}

// BURGER
initMenuBurger();

// MODALE

document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("photoModal");
    const btn = document.querySelector(".edit-profile-btn");
    const span = document.querySelector(".close-modal");

    // Quand on clique sur le stylo
    btn.onclick = function (e) {
        e.preventDefault();
        modal.style.display = "block";
    };

    // Quand on clique sur la petite croix (si tu en as mis une)
    if (span) {
        span.onclick = function () {
            modal.style.display = "none";
        };
    }

    // Quand on clique n'importe où en dehors de la modale
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };

    const cancelBtn = document.querySelector(".btn-cancel");

    if (cancelBtn) {
        cancelBtn.onclick = function () {
            modal.style.display = "none";
        };
    }
});
