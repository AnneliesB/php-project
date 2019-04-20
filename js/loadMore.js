//AJAX check for email and username via AXIOS

//inital posts already shown on pageload
let posts = 2;

//get feed container, so we can later append elements to this.
const feed = document.querySelector('.feed');

//Select loadMore btn and listen for a click
const loadMoreBtn = document.querySelector(".loadMoreBtn");
loadMoreBtn.addEventListener("click", function(e){
    console.log('click');
    //save current posts shown
    shownPosts = posts;
    
    //increase posts to retrieve
    posts +=2;

    //make Ajax call via Axios to loadMore.php
    axios.post('ajax/load_more.php',{
        shownPosts: shownPosts
    })

    //response
    .then(function (response) {

        //Check response in console
        console.log(response.data);

        //update UI with posts by looping over them
        response.data.forEach(elem => {
            console.log(elem);
            
            //create postContainer div and add it's class
            let postContainer = document.createElement("div");
            postContainer.classList.add("postContainer");

            //create template to put inside the postContainer div
            let postTemplate = `
                    <div class="postTopBar">
                        <div class="postUsername">${elem['username']}</div>
                        <img class="icon postOptions" src="images/menu.svg" alt="options icon">
                    </div>

                    <img class="postImg" src="images/${elem['url_cropped']}"> 
                    <p class="postDescription">${elem['description']}</p>

                    <div class="postStats">
                        <div>
                            <a href=""><img class="icon postLikeIcon" src="images/like.svg" alt="like icon"></a>
                            <p class="postLikes">0</p>
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
            let lastPost = feed.lastChild.previousSibling
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