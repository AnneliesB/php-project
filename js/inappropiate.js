$("a.inappropiate").on("click", function (e) {
    //var postId = $(this).data("id");
    //var link = $(this);

    console.log('ok');

    $.ajax({
        method: "POST",
        url: "ajax/inappropiate.php",
        data: {  },
        dataType: 'json'
    })
    .done(function (res) {
        if(res.status == "success"){
            // Message if it is success
        }
    });

    e.preventDefault();
});