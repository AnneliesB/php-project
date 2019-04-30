//AJAX check for email and username via AXIOS

//inital posts already shown on pageload
let posts = 2;

//get the value of $_GET['query] when searching so we can pass this to PHP ajax file (needed for checking if search has been done)
let url_string = window.location.href;
let url = new URL(url_string);
let searchQuery = url.searchParams.get("query");

//get feed container, so we can later append elements to this.
const feed = document.querySelector('.feed');

//Select loadMore btn and listen for a click
const loadMoreBtn = document.querySelector(".loadMoreBtn");
loadMoreBtn.addEventListener("click", function(e){

    //save current posts shown
    shownPosts = posts;

    //increase posts to retrieve
    posts +=2;

    //make Ajax call via Axios to loadMore.php
    axios.post('ajax/load_more.php',{
        shownPosts: shownPosts,
        searchQuery: searchQuery
    })

    //response
    .then(function (response) {
        console.log(response.data);
        //update UI with posts by looping over them
        response.data.forEach(elem => {

            //create postContainer div and add it's class
            let postContainer = document.createElement("div");
            postContainer.classList.add("postContainer");

            //Check if liked this post to provide right like icon
            if( elem['hasLiked'] == true){
                $likeImage = "liked.svg";
            }
            else{
                $likeImage = "like.svg";
            }

            //create template to put inside the postContainer div
            let postTemplate = `
                <div class="postTopBar">
                    <div class="postUsername">${elem['username']}</div>
                    <p> ${elem['ago']} </p>
                    <a href="#" class="options"><img class="icon postOptions" src="images/menu.svg" alt="options icon"></a>
                </div>

                <a href="details.php?id=${elem['id']}"><img class="postImg" src="images/${elem['url_cropped']}"></a>
                <p class="postDescription">${elem['description']}</p>

                <div class="postStats">
                    <div>
                        <a href="#" data-id="${elem['id']}" class="like"><img class="icon postLikeIcon"
                        src="images/${$likeImage}"
                        alt="like icon"></a>
                        <p class="postLikes">${elem['likeAmount']}</p>
                    </div>
                    <div>
                        <p class="postComments">0</p>
                        <img class="icon postCommentIcon" src="images/comment.svg" alt="comments icon">  
                    </div>
                </div>
                
                <form>
                    <input class="commentInput" type="text" name="comment" placeholder="comment...">
                    <input class="commentBtn" type="submit" value="Post">
                </form>
            `;

            //put the template inside the postContainer div
            postContainer.innerHTML = postTemplate;

            //get last post in the feed (because lastchild is the button!)
            let lastPost = feed.lastChild.previousSibling;
            //append new post after the last post
            lastPost.parentNode.insertBefore(postContainer, lastPost);


        });

    })

    //catch error
    .catch(function (error) {
        console.log(error);
    });

    e.preventDefault();
});
