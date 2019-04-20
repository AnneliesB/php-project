//AJAX check for email and username via AXIOS

//Select loadMore btn and listen for a click
const loadMoreBtn = document.querySelector(".loadMoreBtn");
loadMoreBtn.addEventListener("click", function(e){


    //make Ajax call via Axios to loadMore.php
    axios.post('ajax/load_more.php',{
        //something: something
    })

    //response
    .then(function (response) {

        //Check response in console
        console.log(response.data);
        
        //update UI with posts
                
    })

    //catch error
    .catch(function (error) {
        console.log(error);
    });

    e.preventDefault();
});