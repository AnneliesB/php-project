$(".feed").on("click", function (e) {
    // select all the inappropriate buttons
    if (e.target.matches("img.inappropriateIcon")) {
        // get the link around the image
        let inappropriateBtn = e.target.parentElement;

        // get the id of the post
        let postId = inappropriateBtn.dataset.id;

        // link of the button to set the styling
        let link = inappropriateBtn;

        // https://api.jquery.com/parent/
        let post = inappropriateBtn.parentElement.parentElement.parentElement;

        console.log(postId);

        $.ajax({
                method: "POST",
                url: "ajax/inappropriate.php",
                data: {
                    postId: postId
                },
                dataType: 'json'
            })
            .done(function (res) {
                if (res.status == "Success") {
                    // Message if it is success
                    alert(res.message);

                    // Add css
                    link.style.pointerEvents = "none";
                    link.style.opacity = "0.5";
                    
                } else if (res.status == "Disable") {
                    // Message if it is disable
                    alert(res.message);

                    // Add css
                    link.style.pointerEvents = "none";
                    link.style.opacity = "0.5";

                    // Disable post                  
                    post.style.display = "none";
                }

            });

        e.preventDefault();
    }

});