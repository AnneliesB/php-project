const filterOption = document.querySelector(".filterContainer");
const image = document.getElementById('output');

filterOption.addEventListener('click', function (e){
    /*let filter = filterOption.children;
    let filterbtn = filter[0].children;
    let actualfilter = filterbtn[0].className;
    console.log(actualfilter);*/

    if (e.target.matches(".filterOptions")) {
        filter = e.target.parentElement.className;
        image.parentElement.className=filter;

        // insert filtername into hidden input field to save in database when posted
        let inputFilter = document.querySelector('#filterDb');
        inputFilter.value = filter;
    }
});
