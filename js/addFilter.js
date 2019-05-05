const filterOption = document.querySelector(".filterContainer");
const image = document.getElementById('output');

filterOption.addEventListener('click', function (e){
    /*let filter = filterOption.children;
    let filterbtn = filter[0].children;
    let actualfilter = filterbtn[0].className;
    console.log(actualfilter);*/

    if (e.target.matches(".filterOptions")) {
        filter = e.target.parentElement.className;
        console.log(filter);
        image.parentElement.className=filter;
    }
});
