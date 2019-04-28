$("a.like").on("click", function (e) {
    let postId = $(this).data("id");
    let link = $(this);
    console.log("test");

    $.ajax({
        method: "POST",
        url: "ajax/likePost.php",
        data: {postId: postId},
        dataType: 'json'
    })
        .done(function (res) {
            if (res.status == "liked") {
                let likes = link.next().html();
                link.children().attr("src", "images/liked.svg");
                likes++;
                link.next().html(likes);
            } else {
                let likes = link.next().html();
                link.children().attr("src", "images/like.svg");
                likes--;
                link.next().html(likes);
            }
        });

    e.preventDefault();
});
