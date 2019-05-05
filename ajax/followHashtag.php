<?php
    require_once("../bootstrap/bootstrap.php");

    //Get passed data from axios call
    $data = json_decode(file_get_contents("php://input"), true);

    // get name of hashtag
    $hashtag = $data['hashtag'];

    //Get user ID of currently logged in user
    $userId = User::getUserId();

    //Get connection with DB
    $conn = Db::getConnection();

    //check if following this user to see if we follow or unfollow
    $follows = Follow::isFollowingHashTag($userId, $hashtag);
    if( $follows === "Follow"){

        //we can follow this user (not following yet!)
        //insert new record for current user following this profile
        $statement = $conn->prepare("insert into hashtag (user_id, hashtag) values (:user_id, :hashtag)");
        $statement->bindParam(":user_id", $userId);
        $statement->bindParam(":hashtag", $hashtag);
        $statement->execute();
        
        $response['status'] = 'following';

        

    }
    else{

        //we can unfollow this user (following already!)
        // delete hashtag tahat user follows
        $statement = $conn->prepare("delete from followers where user_id = :user_id and hashtag = :hashtag");
        $statement->bindParam(":user_id", $userId);
        $statement->bindParam(":hashtag", $hashtag);
        $statement->execute();
        
        $response['status'] = 'unfollowing';

        
    }

echo json_encode($response);

    

    