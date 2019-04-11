<?php
    require_once("../bootstrap/bootstrap.php");

    //Get email from axios post
    $data = json_decode(file_get_contents("php://input"), true);
    $email = $data['email'];
    

    $result = [];
    if( User::isEmailAvailable($email) ){
        //Email is available
        $result = [
            "status" => "success",
            "message" => "Email is available"
        ];

    }else{
        //Email is NOT available
        $result = [
            "status" => "error",
            "message" => "A user with this email address is already registered."
        ];

    }

    echo json_encode($result);