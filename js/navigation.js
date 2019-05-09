let clicked = false;
let hamburger = document.querySelector(".hamburger");
let nav = document.querySelector(".mobileNav");

hamburger.addEventListener("click", function () {
    if (clicked == false) {
        nav.style.display = "block";
        clicked = true;
    } else {
        nav.style.display = "none";
        clicked = false;
    }
});

window.addEventListener('resize', function(){
    nav.style.display = "none";
    clicked = false;
})