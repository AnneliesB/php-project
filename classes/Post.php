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
}