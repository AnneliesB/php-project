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
    public static function isFollowing($user_id, $following_id)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select * from followers where user_id = :user_id and following_id = :following_id ");
        $statement->bindParam(":user_id", $user_id);
        $statement->bindParam(":following_id", $following_id);
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

}