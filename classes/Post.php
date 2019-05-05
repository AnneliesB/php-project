<?php

Post::setConn();

class Post {
    static private $conn;

    public static function setConn() {
        self::$conn = Db::getConnection();
    }

    public static function getPostById(int $post) {
        try{
            $statement = self::$conn->prepare("select * from photo where id = :id");
            $statement->bindParam(":id", $post);
            $statement->execute();
            $post = $statement->fetch(PDO::FETCH_ASSOC);
            return $post;
        } catch (\PDOException $e){
            // Log to error file
            return false;
        }
    }

    public static function getCommentsByPostId(int $post) {
        try {
            $commentStatement = self::$conn->prepare("select comment.*, user.username from comment inner join user on comment.user_id = user.id where post_id = :postId");
            $commentStatement->bindParam(":postId", $post);
            $commentStatement->execute();
            $comments = $commentStatement->fetchAll();
            return $comments;
        }catch (\PDOException $e){
            return false;
        }
    }

    // AND enable=0
    public static function getAllEnabledPostsForUser(int $userId, int $limit=2) {
        try{
             $statement = self::$conn->prepare("SELECT photo.*, user.username, photo.id FROM photo INNER JOIN user ON photo.user_id = user.id WHERE user_id IN ( SELECT following_id FROM followers WHERE user_id = :user_id ) AND photo.inappropriate = 0 AND enable=0 order by id desc limit 2");
            // $statement = self::$conn->prepare("SELECT photo.*, poster.id from photo JOIN `user` AS poster ON photo.user_id = poster.id WHERE user_id IN (SELECT following_id FROM followers WHERE user_id=) LIMIT $limit");
            //$statement = self::$conn->prepare("select photo.*, user.username, photo.id from photo INNER JOIN user ON photo.user_id = user.id where user_id IN ( select following_id from followers where user_id = :user_id ) and photo.inappropriate = 0 order by id desc limit 2");
            // $statement->bindparam(":limit", $limit);
            $statement->bindParam(":user_id", $userId);
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            //var_dump($results);
            //die(json_encode($results));
            return $results;
        } catch (\PDOException $e){
            return false;
        }
    }

    public static function getPostByIdAndUserId(){

    }
}
