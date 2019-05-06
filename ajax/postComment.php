<?php
require_once("../bootstrap/bootstrap.php");

$uid = User::getUserId();
$pid = htmlspecialchars($_GET["postid"]);
$comment = htmlspecialchars($_GET["comment"]);
$time = htmlspecialchars($_GET["date"]);

try{
    $conn = Db::getConnection();
    //QUERIE WEG IER 
    $statement = $conn->prepare("INSERT INTO comment (`comment`, `date`, `post_id`, `user_id`) VALUES (:comment, :date, :post_id, :user_id);");
    $statement->bindParam(":comment", $comment);
    $statement->bindParam(":date", $time);
    $statement->bindParam(":post_id", $pid);
    $statement->bindParam(":user_id", $uid);
    $statement->execute();
}catch(Exception $e){
    \phpproject\classes\Logger::logError($e->getMessage());
}

die(json_encode([
    $uid,
    $pid,
    $comment,
    $time
])); 
//GEEN QUERIES IER!!
$commentStatement = $conn->prepare("select comment.*, user.username from comment inner join user on comment.user_id = user.id where post_id = :postId");
    $commentStatement->bindParam(":postId", $pid);
    $commentStatement->execute();
    $comments = $commentStatement->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($comments);