<?php

# require bootstrap
require_once("../bootstrap/bootstrap.php");

# check if a session is running with the correct email
if (isset($_SESSION['email'])) {
    //User is logged in, no redirect needed!
} else {
    //User is not logged in, redirect to login.php!
    header("location: login.php");
}

# connect to the database
$conn = Db::getConnection();

# get clicked post info from database
$postId = $_POST['postId'];

# create empty response array
$response = [];

# get user info from database (get user id based on the session cookie email)
$sessionEmail = $_SESSION['email'];
$statement = $conn->prepare("SELECT * from user where email = :sessionEmail");
$statement->bindParam(":sessionEmail", $sessionEmail);
$statement->execute();
$currentUser = $statement->fetch(PDO::FETCH_ASSOC);

$userId = $currentUser['id'];

# check if a record exists in the likes table where the current post's id and current user's id are available
$likeStatement = $conn->prepare("SELECT count(*) as count from likes where post_id = :postId AND user_id = :userId");
$likeStatement->bindParam(":postId", $postId);
$likeStatement->bindParam(":userId", $userId);
$likeStatement->execute();
$recordAmount = $likeStatement->fetch(PDO::FETCH_ASSOC);

# if 0 records found => insert new record into the likes table with the current post id, user id en a true liked status
if ($recordAmount['count'] == 0) {
    # first like, so set liked_status to 1
    $liked_status = 1;

    # insert new record
    $insertLikeStatement = $conn->prepare("INSERT INTO likes (post_id, user_id, liked_status) values (:post_id, :user_id, :liked_status)");
    $insertLikeStatement->bindParam(":post_id", $postId);
    $insertLikeStatement->bindParam(":user_id", $userId);
    $insertLikeStatement->bindParam(":liked_status", $liked_status);
    $insertLikeStatement->execute();

    $response['status'] = 'liked';

} else {
    # check if liked status is true or false
    $getLikeStatusStatement = $conn->prepare("SELECT liked_status from likes where post_id = :postId AND user_id = :userId");
    $getLikeStatusStatement->bindParam(":postId", $postId);
    $getLikeStatusStatement->bindParam(":userId", $userId);
    $getLikeStatusStatement->execute();
    $currentLikedStatus = $getLikeStatusStatement->fetch(PDO::FETCH_ASSOC);

    # if post is liked (liked_status 1) change status || if post is not liked, change status
    if ($currentLikedStatus['liked_status'] == 1) {
        $liked_status = 0;
        $response['status'] = 'unliked';
    } else {
        $liked_status = 1;
        $response['status'] = 'liked';
    }

    #update record to contain new liked_status
    $updateStatement = $conn->prepare("update likes set liked_status= :liked_status where post_id = :postId AND user_id = :userId");
    $updateStatement->bindParam(":postId", $postId);
    $updateStatement->bindParam(":userId", $userId);
    $updateStatement->bindParam(":liked_status", $liked_status);
    $updateStatement->execute();
}

header('Content-Type: application/json');
echo json_encode($response);
