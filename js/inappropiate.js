$("a.inappropriate").on("click", function (e) {
    let postId = $(this).data("id");
    let link = $(this);
    // https://api.jquery.com/parent/
    let post = $(this).parent().parent();


    console.log(postId);

    $.ajax({
        method: "POST",
        url: "ajax/inappropiate.php",
        data: { postId: postId },
        dataType: 'json'
    })
    .done(function (res) {
        if(res.status == "Success"){
            // Message if it is success
            alert(res.message);

            // Add css
            link.css("pointer-events", "none");
            link.css("text-decoration", "none");
            link.css("opacity", "0.5");
        }
        
        else if (res.status == "Disable" ) {
            // Message if it is disable
            alert(res.message);

            // Add css
            link.css("pointer-events", "none");
            link.css("text-decoration", "none");
            link.css("opacity", "0.5");

            // Disable post
            post.css("display", "none");
        }

    });

    e.preventDefault();
});