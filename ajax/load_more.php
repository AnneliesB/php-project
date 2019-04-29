<?php
    require_once("../bootstrap/bootstrap.php");

    //Get passed data from axios call
    $data = json_decode(file_get_contents("php://input"), true);
    //get start point of new posts to load
    $startpoint = $data['shownPosts'];
    //get searchquery (we'll check if there is one passed later)
    $searchQuery = $data['searchQuery'];

    //Get connection with DB
    $conn = Db::getConnection();

    //Get user ID of currently logged in user
    $user_id = User::getUserId();

    //check if we are on a search results page (to show only search results and not actual index posts on loading more)
    if( $searchQuery !== null){

        //we are on a search results page, show more search results!
        $statement = $conn->prepare("select photo.*, user.username from photo INNER JOIN user ON photo.user_id = user.id where photo.description like '%". $searchQuery ."%' and photo.inappropriate = 0 order by id desc LIMIT $startpoint, 2"); 
        $statement->execute();   
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        //loop over all posts in results and add extra info we need into the results array for the js post template
        for($i = 0; $i < sizeof($results); $i++){

            //get the amount of likes for each post and update the results array
            $likeAmount = Like::getLikeAmount($results[$i]['id']);
            $results[$i]["likeAmount"] = $likeAmount;

            //get the likedstatus for this user on each post and update the results array
            $hasliked = Like::userHasLiked($results[$i]['id'], $user_id);
            $results[$i]["hasLiked"] = $hasliked;
        }

    }else{

        //We are not on a search results page => Get posts from DB and put them in $results
        $statement = $conn->prepare("select photo.*, user.username from photo INNER JOIN user ON photo.user_id = user.id where user_id IN ( select following_id from followers where user_id = :user_id ) and photo.inappropriate = 0 order by id desc limit $startpoint, 2");
        $statement->bindParam(":user_id", $user_id);
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        //loop over all posts in results and add extra info we need into the results array for the js post template
        for($i = 0; $i < sizeof($results); $i++){

            //get the amount of likes for each post and update the results array
            $likeAmount = Like::getLikeAmount($results[$i]['id']);
            $results[$i]["likeAmount"] = $likeAmount;

            //get the likedstatus for this user on each post and update the results array
            $hasliked = Like::userHasLiked($results[$i]['id'], $user_id);
            $results[$i]["hasLiked"] = $hasliked;
        }

    }
    


    echo json_encode($results);