$(".commentBtn").on("click", function (e) {
  let postId = $(this).data("id");

  let link = $(this);

  let comment = $(this).prev().val();
  alert(comment + "oke");

  $.ajax({
        method: "POST",
        url: "ajax/comment.php",
        data: {postId: postId, comment: comment},
        dataType: 'json'
    })
    .done(function (res) {
          alert('voor if');
            if (res.status == "commented") {

              alert('saved');
                // show comment
                // html special chars

            }

            else if (res.status == "empty comment") {
                // nothing happen
                console.log("in de else");
                alert('kapot');
            }
        });
         e.preventDefault();
});
