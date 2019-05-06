<?php
    require_once("../bootstrap/bootstrap.php");
    // GET id of post
    $id = $_GET['id'];
    //var_dump($id);
    // Connection
    $conn = Db::getConnection();
    // GET description and picture
    $statement = $conn->prepare("select * from photo where id = :id");
    $statement->bindParam(":id", $id);
    $statement->execute();
    $post = $statement->fetch(PDO::FETCH_ASSOC);
    // GET comments

  
    $commentStatement = $conn->prepare("select comment.*, user.username from comment inner join user on comment.user_id = user.id where post_id = :postId");
    $commentStatement->bindParam(":postId", $id);
    $commentStatement->execute();
    $comments = $commentStatement->fetchAll();
    echo json_encode($comments);