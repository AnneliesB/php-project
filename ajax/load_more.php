<?php
    require_once("../bootstrap/bootstrap.php");

    //Get connection with DB
    $conn = Db::getConnection();

    //Get user ID of currently logged in user
    $user_id = User::getUserId();

    //Get posts from DB and put them in $results
    $statement = $conn->prepare("select photo.*, user.username from photo INNER JOIN user ON photo.user_id = user.id where user_id IN ( select following_id from followers where user_id = :user_id ) order by id desc limit 20");
    $statement->bindParam(":user_id", $user_id);
    $statement->execute();
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);


    echo json_encode($results);