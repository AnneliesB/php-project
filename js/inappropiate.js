$("a.inappropiate").on("click", function (e) {
    let postId = $(this).data("id");
    //let link = $(this);

    console.log(postId);

    $.ajax({
        method: "POST",
        url: "ajax/inappropiate.php",
        data: { postId: postId },
        dataType: 'json'
    })
    .done(function (res) {
        if(res.status == "success"){
            // Message if it is success
            console.log("success");
        }
    });

    e.preventDefault();
});