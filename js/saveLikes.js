let knop = document.querySelector("a.like");
knop.addEventListener("click", function(e){
    let postId = this.dataset.id;
    let link = this;
    console.log("test");
    console.log(postId + " post id");

    axios.post('ajax/likePost.php',{
        postId : postId

    })
        .then (function (res){
            console.log(res.status + "ne res");
            let img = document.querySelector(".postLikeIcon");
            if (res.status == "liked") {
                console.log("we zitten ion de liked");

                let likes = link.nextSibling.innerHTML;
                link.children.src = "images/liked.svg";
                likes++;
                link.nextSibling.innerHTML=likes;
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