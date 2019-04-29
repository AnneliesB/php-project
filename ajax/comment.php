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
  $comment = $_POST['comment'];




  $conn = Db::getConnection();
  $userId = User::getUserId();

  if(!empty($_POST['comment'])) {

    $statement = $conn->prepare("insert into comment (user_id, post_id, comment) values (:userId, :postId, :comment)");
    $statement->bindParam(":postId", $postId);
    $statement->bindParam(":userId", $userId);
    $statement->bindParam(":comment", $comment);
    $statement->execute();

    $response['status'] = 'commented';

  }

  else {
    $response['status'] = 'empty comment';
  }

  header('Content-Type: application/json');
  echo json_encode($response);
