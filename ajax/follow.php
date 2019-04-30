<?php
    require_once("../bootstrap/bootstrap.php");

    //Get passed data from axios call
    $data = json_decode(file_get_contents("php://input"), true);
    //get id of the user/profile we want to follow
    $following_id = $data['following_id'];

    //Get user ID of currently logged in user
    $user_id = User::getUserId();

    //Get connection with DB
    $conn = Db::getConnection();

    //check if following this user to see if we follow or unfollow
    $follows = Follow::isFollowing($user_id, $following_id);
    if( $follows === "Follow"){

        //we can follow this user (not following yet!)
        //insert new record for current user following this profile
        $statement = $conn->prepare("insert into followers (user_id, following_id) values (:user_id, :following_id)");
        $statement->bindParam(":user_id", $user_id);
        $statement->bindParam(":following_id", $following_id);
        $statement->execute();
        
        $response['status'] = 'following';

    }
    else{

        //we can unfollow this user (following already!)
        //delete record for current user following this profile
        $statement = $conn->prepare("delete from followers where user_id = :user_id and following_id = :following_id");
        $statement->bindParam(":user_id", $user_id);
        $statement->bindParam(":following_id", $following_id);
        $statement->execute();
        
        $response['status'] = 'unfollowing';
    }

echo json_encode($response);

    

    