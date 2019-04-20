//AJAX check for email and username via AXIOS
 
//inital posts already shown on pageload
let posts = 2;

//Select loadMore btn and listen for a click
const loadMoreBtn = document.querySelector(".loadMoreBtn");
loadMoreBtn.addEventListener("click", function(e){
    //save current posts shown
    shownPosts = posts;
    
    //increase posts to retrieve
    posts +=2;

    //make Ajax call via Axios to loadMore.php
    axios.post('ajax/load_more.php',{
        shownPosts: shownPosts
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