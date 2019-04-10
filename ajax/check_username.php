<?php
    require_once("../bootstrap/bootstrap.php");

    //Get username from axios post
    $data = json_decode(file_get_contents("php://input"), true);
    $username = $data['username'];
    

    $result = [];
    if( User::isUsernameAvailable($username) ){
        //Username is available
        $result = [
            "status" => "success",
            "message" => "Username is available"
        ];

    }else{
        //Username is NOT available
        $result = [
            "status" => "error",
            "message" => "This username is already registered."
        ];

    }

    echo json_encode($result);