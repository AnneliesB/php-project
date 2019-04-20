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


    public static function getLikeAmount($postId){
        $conn = Db::getConnection();
        // get post id from database
        $statement = $conn->prepare("select count(*) as count from likes where post_id = :postid AND liked_status='1'");
        $statement->bindValue(":postid", $postId);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result['count'];

    }
}