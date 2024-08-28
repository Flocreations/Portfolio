// Menu burger

const menu = document.querySelector(".menu");
const hamburger = document.querySelector(".hamburger");
const closeButton = document.querySelector(".closeButton");
// const menuIcon = document.querySelector(".menuIcon");

function toggleMenu() {

    if (menu.classList.contains("showMenu")) {
        menu.classList.remove("showMenu");
        closeButton.style.display = "none";
    } else {
        menu.classList.add("showMenu");
        closeButton.style.display = "block";
    }
}

hamburger.addEventListener("click", toggleMenu);
closeButton.addEventListener("click", toggleMenu);