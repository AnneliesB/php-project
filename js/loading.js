var btnPost = document.querySelector('.btnPost');
var loaderIcon = document.querySelector('#loaderIcon');
btnPost.addEventListener("click", function(e){
    loaderIcon.classList.remove("hidden");
    loaderIcon.classList.add("fa", "fa-circle-o-notch", "fa-spin");
    
})