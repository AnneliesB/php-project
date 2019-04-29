//Get all like buttons
let knop = document.querySelectorAll("span.like");

//loop over all like buttons in array and listen for a click
for (let i = 0; i < knop.length; i++) {
    knop[i].addEventListener("click", function(e) {
      
    let postId = this.dataset.id;
    let link = this;
    let likes = this.nextElementSibling;
    let image = this.firstChild;
    console.log(image);
    console.log("test");

    $.ajax({
        method: "POST",
        url: "ajax/likePost.php",
        data: {postId: postId},
        dataType: 'json'
    })
        .then (function (res){
            console.log(res.data);
            let img = document.querySelector(".postLikeIcon");
            if (res.data['status'] === "liked") {
                console.log("we zitten in de liked");

                let counter = likes.innerHTML;
                console.log(counter + " de likes");
                image.src = "images/liked.svg";
                counter++;
                likes.innerHTML=counter;
            } else {
                console.log("we zitten in de niet liked");
                let counter = likes.innerHTML;
                console.log(counter + " de likes");
                image.src = "images/like.svg";
                counter--;
                likes.innerHTML=counter;
            }
        });

    e.preventDefault();

    });
}
