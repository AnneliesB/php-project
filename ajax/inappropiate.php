<?php
    require_once("../bootstrap.php");

    if (!empty($_POST)) {
        $postId = $_POST['postId'];
        $userId = User::getUserId();

        var_dump($userId);

        $result = [
            "status" => "success",
            "message" => "Inappropiate was saved"
        ];

        echo json_encode($result);

    }