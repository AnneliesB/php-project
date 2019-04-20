<?php
require_once("bootstrap/bootstrap.php");

class Likes
{
    private $user_id;
    private $post_id;
    private $liked_status;

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getPostId()
    {
        return $this->post_id;
    }

    /**
     * @param mixed $post_id
     */
    public function setPostId($post_id)
    {
        $this->post_id = $post_id;
    }

    /**
     * @return mixed
     */
    public function getLikedStatus()
    {
        return $this->liked_status;
    }

    /**
     * @param mixed $liked_status
     */
    public function setLikedStatus($liked_status)
    {
        $this->liked_status = $liked_status;
    }


    public static function getLikeAmount($postId)
    {
        $conn = Db::getConnection();
        // get post id from database
        $statement = $conn->prepare("select count(*) as count from likes where post_id = :postid AND liked_status='1'");
        $statement->bindValue(":postid", $postId);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result['count'];

    }

    public static function userHasLiked($postId, $userId)
    {
        $conn = Db::getConnection();
        $statementCheck = $conn->prepare("SELECT count(*) as count from likes where post_id = :postId AND user_id = :userId AND liked_status='1'");
        $statementCheck->bindParam(":postId", $postId);
        $statementCheck->bindParam(":userId", $userId);
        $statementCheck->execute();
        $result = $statementCheck->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] == 0) {
            # there are no records with this combo, so no likes from this user on this post yet
            return false;
        } else {
            # there is a record from this user on this post where the liked_status is true
            return true;
        }

    }
}