//Get all like buttons
let knop = document.querySelectorAll("span.like");

//loop over all like buttons in array and listen for a click
for (let i = 0; i < knop.length; i++) {
    knop[i].addEventListener("click", function() {
      
    let postId = this.dataset.id;
    let link = this;
    let likes = this.nextSibling;
    let image = this.children;
    console.log("test");

    axios.post('ajax/likePost.php',{
        postId : postId

    })
        .then (function (res){
            console.log(res);
            let img = document.querySelector(".postLikeIcon");
            if (res.data['status'] === "liked") {
                console.log("we zitten in de liked");

                let counter = likes.innerHTML;
                console.log(counter + " de likes");
                image.src = "images/liked.svg";
                counter++;
                counter.innerHTML=likes;
            } else {
                console.log("we zitten in de niet liked");
                let counter = likes.innerHTML;
                console.log(counter + " de likes");
                image.src = "images/like.svg";
                counter--;
                counter.innerHTML=likes;
            }
        })
        .catch(function (error) {
            console.log(error);
        });


    e.preventDefault();

    });
}