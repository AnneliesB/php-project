<?php

require_once("bootstrap/bootstrap.php");

$conn = Db::getConnection();

// get email from current user
// $_SESSION['email']
$email = $_SESSION['email'];


// GET data from DB
$statement = $conn->prepare("select * from user where email = :email");
$statement->bindParam(":email", $email);
$statement->execute();
$profile = $statement->fetch(PDO::FETCH_ASSOC);
// Edit button click
// Pop-up: ask old password
// Go to editProfile.php


?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>IMSTAGRAM - profile</title>
</head>
<body>
<!-- SHOW data from DB -->
<div class="profile">
    <h2>Profile</h2>

    <!-- echo profile picture -->
    <?php if ($profile['image'] != "filler.png"): ?>
        <img src="images/profilePictures/<?php echo $profile['id'] . $profile['image']; ?>" alt="Profile Picture"
             class="profilePicture">
    <?php else: ?>
        <img src="images/profilePictures/filler.png" alt="Profile Picture"
             class="profilePicture">
    <?php endif ?>

    <!-- echo username -->
    <h3><?php echo $profile['username']; ?></h3>

    <!-- echo email -->
    <div class="profileContainer">
        <p class="profileLabel">Email</p>
        <p><?php echo $profile['email']; ?></p>
    </div>

    <!-- echo description -->
    <div class="profileContainer lastItem">
        <p class="profileLabel">Description</p>
        <p><?php echo $profile['description']; ?></p>
    </div>


    <a href="editProfile.php" class="btnProfile"> Edit Profile</a>
    <a href="editPassword.php" class="btnProfile"> Edit Password</a>
    <a href="logout.php" class="btnProfile">Logout</a>
</div>


</body>
</html>
