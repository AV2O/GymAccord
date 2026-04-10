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