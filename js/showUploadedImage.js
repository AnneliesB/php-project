var uploader = document.getElementById('image');

image.addEventListener("change", function(event){
    // select all hidden elements and show them as soon as an image is selected
    var elements = document.querySelector(".filters");
    var imageWrapper = document.querySelector(".imageWrapper");
    elements.style.display="block";
    imageWrapper.style.display="block";
    var image = document.getElementById('output');

    var F_nofilter = document.querySelector(".F_nofilter");
    var F_1977 = document.querySelector(".F_1977");
    var F_aden = document.querySelector(".F_aden");
    var F_brannan = document.querySelector(".F_brannan");
    var F_brooklyn = document.querySelector(".F_brooklyn");
    var F_clarendon = document.querySelector(".F_clarendon");
    var F_earlybird = document.querySelector(".F_earlybird");
    var F_gingham = document.querySelector(".F_gingham");
    var F_hudson = document.querySelector(".F_hudson");
    var F_inkwell = document.querySelector(".F_inkwell");
    var F_kelvin = document.querySelector(".F_kelvin");
    var F_lark = document.querySelector(".F_lark");
    var F_lofi = document.querySelector(".F_lofi");
    var F_maven = document.querySelector(".F_maven");
    var F_mayfair = document.querySelector(".F_mayfair");
    var F_moon = document.querySelector(".F_moon");
    var F_nashville = document.querySelector(".F_nashville");
    var F_perpetua = document.querySelector(".F_perpetua");
    var F_reyes = document.querySelector(".F_reyes");
    var F_rise = document.querySelector(".F_rise");
    var F_slumber = document.querySelector(".F_slumber");
    var F_stinson = document.querySelector(".F_stinson");
    var F_toaster = document.querySelector(".F_toaster");
    var F_valencia = document.querySelector(".F_valencia");
    var F_walden = document.querySelector(".F_walden");
    var F_willow = document.querySelector(".F_willow");

    var img = event.target.files[0];



    image.src = URL.createObjectURL(img);
    F_nofilter.src=URL.createObjectURL(img);
    F_1977.src=URL.createObjectURL(img);
    F_aden.src=URL.createObjectURL(img);
    F_brannan.src=URL.createObjectURL(img);
    F_brooklyn.src=URL.createObjectURL(img);
    F_clarendon.src=URL.createObjectURL(img);
    F_earlybird.src=URL.createObjectURL(img);
    F_gingham.src=URL.createObjectURL(img);
    F_hudson.src=URL.createObjectURL(img);
    F_inkwell.src=URL.createObjectURL(img);
    F_kelvin.src=URL.createObjectURL(img);
    F_lark.src=URL.createObjectURL(img);
    F_lofi.src=URL.createObjectURL(img);
    F_maven.src=URL.createObjectURL(img);
    F_mayfair.src=URL.createObjectURL(img);
    F_moon.src=URL.createObjectURL(img);
    F_nashville.src=URL.createObjectURL(img);
    F_perpetua.src=URL.createObjectURL(img);
    F_reyes.src=URL.createObjectURL(img);
    F_rise.src=URL.createObjectURL(img);
    F_slumber.src=URL.createObjectURL(img);
    F_stinson.src=URL.createObjectURL(img);
    F_toaster.src=URL.createObjectURL(img);
    F_valencia.src=URL.createObjectURL(img);
    F_walden.src=URL.createObjectURL(img);
    F_willow.src=URL.createObjectURL(img);

})
