let knop = document.querySelector("a.like");
knop.addEventListener("click", function(e){
    let postId = this.dataset.id;
    let link = this;
    console.log("test");

    axios.post('ajax/likePost.php',{
        postId : postId

    })
        .then (function (res){
            console.log(res);
            let img = document.querySelector(".postLikeIcon");
            if (res.data['status'] === "liked") {
                console.log("we zitten ion de liked");

                let likes = link.nextSibling.innerHTML;
                console.log(likes + " de likes");
                link.children.src = "images/liked.svg";
                likes++;
                link.children.innerHTML=likes;
            } else {
                console.log("we zitten ion de niet liked");
                let likes = link.nextSibling.innerHTML;
                link.children.src = "images/like.svg";
                likes--;
                link.nextSibling.innerHTML=likes;
            }
        })
        .catch(function (error) {
            console.log(error);
        });


    e.preventDefault();
});