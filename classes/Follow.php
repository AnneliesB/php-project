<?php
require_once("Db.php");

class Follow {
    private $user_id;
    private $following_id;
    private $hashtag;

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function getFollowingId()
    {
        return $this->following_id;
    }

    public function setFollowingId($following_id)
    {
        $this->following_id = $following_id;
        return $this;
    }

    /*
    * Check if following record exists for a given user
    */
    public static function getUserProfile($username)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select * from user where username = :username");
        $statement->bindParam(":username", $username);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    /*
    * Check if following record exists for a given user
    */
    public static function isFollowing($user_id, $hashtag)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select * from followers where user_id = :user_id and hashtag = :hashtag ");
        $statement->bindParam(":user_id", $user_id);
        $statement->bindParam(":hashtag", $hashtag);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if($result === false){
            //we are not following this user
            $followBtn = "Follow";
        }
        else{
            //we are following this user
            $followBtn = "Unfollow";
        }
        return $followBtn;
        
    }

    public static function isFollowingHashTag($userId, $hashtag) {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select * from followers where user_id = :userId and hashtag = :hashtag ");
        $statement->bindParam(":userId", $userId);
        $statement->bindParam(":hashtag", $hashtag);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if($result === false){
            //we are not following this user
            $followBtn = "Follow";
        }
        else{
            //we are following this user
            $followBtn = "Unfollow";
        }
        return $followBtn;
    }

    /*
    * Check if user is on his own profile, so we can't follow ourselves!
    */
    public static function notTryingToFollowMyself($user_id, $profile_id)
    {
        if($user_id !== $profile_id){
            //the logged in user is NOT the same as the profile he's looking at, return true
            return true;
        }else{
            //the logged in user is looking at his own profile, return false
            return false;
        }

    }
    

}