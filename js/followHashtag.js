let followHashtagBtn = document.querySelector('#followHashtagBtn');
followHashtagBtn.addEventListener('click', function(e){


    let hashtag = this.dataset.tag;

    console.log(hashtag);

    //make Ajax call via Axios to loadMore.php
    axios.post('ajax/followHashtag.php', {
        hashtag: hashtag
    })

    //response
    .then(function (response) {
        if(response.data['status'] == 'following'){
            //we are now following this user, update text button to 'unfollow'
            followHashtagBtn.innerHTML = "Unfollow " + hashtag;
        }else{
            //we just unfollowed this user, update text button to 'follow'
            followHashtagBtn.innerHTML = "Follow " + hashtag;
        }
    })

    //catch error
    .catch(function (error) {
        console.log(error);
    });


    e.preventDefault();
    
});
