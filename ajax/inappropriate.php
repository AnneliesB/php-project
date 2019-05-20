<?php
    require_once("../bootstrap/bootstrap.php");

    //Check if user session is active (Is user logged in?)
    User::userLoggedIn();

    $response = []; 
    $postId = $_POST['postId'];
    $userId = User::getUserId();
    $conn = Db::getConnection();

    // Get amount of inappropiate
    // Save count(*) as count to get the amount in php
    $inappropriateStatement = $conn->prepare("select count(*) as count from inappropriate where post_id = :postId AND user_id = :userId");
    $inappropriateStatement->bindParam(":postId", $postId);
    $inappropriateStatement->bindParam(":userId", $userId);
    $inappropriateStatement->execute();
    $inappropriateAmount = $inappropriateStatement->fetch(PDO::FETCH_ASSOC);
    
    // Count amount of inappropiate + 1
    // Get count of rows
    if ($inappropriateAmount['count'] == 0) {
        // Set inappropiate 1
        $inappropriate = 1;

        // Insert new row in db
        $insertinappropriateStatement = $conn->prepare("insert into inappropriate (post_id, user_id, inappropriate) values (:postId, :userId, :inappropriate)");
        $insertinappropriateStatement->bindParam(":postId", $postId);
        $insertinappropriateStatement->bindParam(":userId", $userId);
        $insertinappropriateStatement->bindParam(":inappropriate", $inappropriate);
        $insertinappropriateStatement->execute();     
        
        
        // Count amount of reports
        $statement = $conn->prepare("select count(*) as count from inappropriate where post_id = :postId");
        $statement->bindParam(":postId", $postId);
        $statement->execute();
        $Amount = $statement->fetch(PDO::FETCH_ASSOC);        
        
        if ($Amount['count'] == 3) {
            $updateInappropriateStatement = $conn->prepare("update photo set inappropriate = '1' where id = :postId ");
            $updateInappropriateStatement->bindParam(":postId", $postId);
            $updateInappropriateStatement->execute();

            $response = [
                "status" => "Disable",
                "message" => "This post was reported as inappropriate and has been disabled!"
            ]; 
        } 

        else {
            $response = [
                "status" => "Success",
                "message" => "This post was reported as inappropriate!"
            ]; 
        } 
    }

    else {
        // Nothing happen
    } 

    header('Content-Type: application/json');
    echo json_encode($response);

    