<?php
    require_once("../bootstrap/bootstrap.php");

    // check if a session is running with the correct email
    if (isset($_SESSION['email'])) {
        // User is logged in, no redirect needed!
    } 
    else {
        // User is not logged in, redirect to login.php!
        header("location: login.php");
    }

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
            $response = [
                "status" => "Disable",
                "message" => "Inappropriate was saved and the post is disbaled!"
            ]; 
        } 

        else {
            $response = [
                "status" => "Success",
                "message" => "Inappropriate was saved!"
            ]; 
        } 
    }

    else {
        // Nothing happen
    } 

    header('Content-Type: application/json');
    echo json_encode($response);

    