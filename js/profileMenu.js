let touched = false;
let icon = document.querySelector(".iconPadding");
let menu = document.querySelector(".settingMenu");

icon.addEventListener("click", function () {
    if (touched == false) {
        menu.style.display = "block";
        touched = true;
    } else {
        menu.style.display = "none";
        touched = false;
    }
});