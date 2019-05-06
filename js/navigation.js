let clicked = false;
let hamburger = document.querySelector(".hamburgerLink");
let nav = document.querySelector(".mobileNav");

hamburger.firstChild.addEventListener("click", function () {
    if (clicked == false) {
        nav.style.display = "block";
        clicked = true;
    } else {
        nav.style.display = "none";
        clicked = false;
    }
});