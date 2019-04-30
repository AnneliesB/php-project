const followBtn = document.querySelector('#followBtn');
followBtn.addEventListener('click', function(e){

    let following_id = this.dataset.id;

    //make Ajax call via Axios to loadMore.php
    axios.post('ajax/follow.php',{
        following_id: following_id
    })

    //response
    .then(function (response) {
        
        console.log(response.data);
        if(response.data['status'] == 'following'){
            //we are now following this user, update text button to 'unfollow'
            followBtn.innerHTML = "unfollow";
        }else{
            //we just unfollowed this user, update text button to 'follow'
            followBtn.innerHTML = "follow";
        }

    })

    //catch error
    .catch(function (error) {
        console.log(error);
    });


    e.preventDefault();
});
