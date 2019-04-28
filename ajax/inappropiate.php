<?php
    require_once("../bootstrap.php");

    // check if a session is running with the correct email
    if (isset($_SESSION['email'])) {
        // User is logged in, no redirect needed!
    } else {
        // User is not logged in, redirect to login.php!
        header("location: login.php");
    }

    echo 'ok';

    $postId = $_POST['postId'];
    $userId = User::getUserId();

    echo $postId;

    $result = [
        "status" => "success",
        "message" => "Inappropiate was saved"
    ];


    // Get amount of inappropiate

    // Count amount of inappropiate + 1

    // Update amount of inappropiate to db
        

    echo json_encode($result);

    