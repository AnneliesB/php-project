//Get all like buttons on pageload
const postfeed = document.querySelector(".feed");

postfeed.addEventListener('click', function (e) {
    //check for clicked element in feed that matches the like button
    if (e.target.matches(".postLikeIcon")) {
        // like button (heart img) clicked

            //get parent of the heart img (the actual a.like) because we'll need it's data-id
            likeBtn = e.target.parentElement

            //make variables we need for Axios
            let postId = likeBtn.dataset.id;
            let likes = likeBtn.nextElementSibling;
            let image = likeBtn.firstChild;
        
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

            }
        


});