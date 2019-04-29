//Get all like buttons on pageload
let knop = document.querySelectorAll("a.like");
//start liker function that listens for clicking a like button
liker(knop);


//function that will be called by loadMore.js after loading more post elements (more like buttons)
function getLikeButtons(){
    //we need to redo the get all like buttons for these new posts
    let knop = document.querySelectorAll("a.like");
    //start like function that listens for clicking a like button
    liker(knop);
}

//liker function is the actual Axios (Ajax) call that likes and unlikes!
function liker(knop){

    for (let i = 0; i < knop.length; i++) {
        knop[i].addEventListener("click", function(e) {
          
        let postId = this.dataset.id;
        let likes = this.nextElementSibling;
        let image = this.firstChild;
    
        axios.post('ajax/likePost.php',{
            postId : postId
    
        })
            .then (function (res){
                console.log(res.data);
                let img = document.querySelector(".postLikeIcon");
                if (res.data['status'] === "liked") {
    
                    let counter = likes.innerHTML;
                    image.src = "images/liked.svg";
                    counter++;
                    likes.innerHTML=counter;
                } else {
                    let counter = likes.innerHTML;
                    image.src = "images/like.svg";
                    counter--;
                    likes.innerHTML=counter;
                }
            })
            .catch(function (error) {
                console.log(error);
            });
    
    
        e.preventDefault();
    
        });
    }
}