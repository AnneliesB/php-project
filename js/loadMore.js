//AJAX check for email and username via AXIOS

//Select loadMore btn and listen for a click
const loadMoreBtn = document.querySelector(".loadMoreBtn");
loadMoreBtn.addEventListener("click", function(){


    //make Ajax call via Axios to loadMore.php
    axios.post('ajax/loadMore.php',{
        //something: something
    })

    //response
    .then(function (response) {
        
        //update UI with posts
                
    })

    //catch error
    .catch(function (error) {
        console.log(error);
    });


});