<?php

Post::setConn();

class Post {
    static private $conn;

    public static function setConn(){
        self::$conn = Db::getConnection();
    }

    public static function getPostById(int $post){
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

    public static function getCommentsByPostId(int $post){
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
}