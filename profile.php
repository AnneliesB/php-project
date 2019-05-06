<?php

require_once("bootstrap/bootstrap.php");

//Check if user session is active (Is user logged in?)
if (isset($_SESSION['email'])) {
    //User is logged in, no redirect needed!
} else {
    //User is not logged in, redirect to login.php!
    header("location: login.php");
}

$profile = User::getCurrentUserProfile();
$user_id = User::getUserId();
$userPosts = User::getUserPosts($user_id);

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/normalize.css">
    <title>IMSTAGRAM - profile</title>
</head>
<body>
<?php include_once("nav.incl.php"); ?>
<!-- SHOW data from DB -->
<div class="profile">
    <div class="iconContainer">
        <div class="profileLeft">
            <h2>Profile</h2>
        </div>

        <div class="profileRight">
            <div class="iconPadding">
                <img src="images/settings-work-tool.svg" alt="" class="profileIcon">
            </div>
        </div>
    </div>

    <div class="settingMenu">
        <div class="alignMenuRight">
            <a href="editProfile.php" class="editButtons"> Edit Profile</a>
            <a href="editPassword.php" class="editButtons"> Edit Password</a>
            <a href="logout.php" class="editButtons">Logout</a>
        </div>
    </div>




    <!-- echo profile picture -->
    <?php if ($profile['image'] != "filler.png"): ?>
        <img src="images/profilePictures/<?php echo $profile['id'] . $profile['image']; ?>" alt="Profile Picture"
             class="profilePicture">
    <?php else: ?>
        <img src="images/profilePictures/filler.png" alt="Profile Picture"
             class="profilePicture">
    <?php endif ?>

    <!-- echo username -->
    <h3><?php echo htmlspecialchars($profile['username']); ?></h3>


    <!-- echo email -->
    <div class="profileContainer">
        <p class="profileLabel">Email</p>
        <p><?php echo htmlspecialchars($profile['email']); ?></p>
    </div>

    <!-- echo description -->
    <div class="profileContainer">
        <p class="profileLabel">Description</p>
        <p><?php echo htmlspecialchars($profile['description']); ?></p>
    </div>

    <p class="profileLabel">Your Posts</p>
    <div class="userPosts">
        <?php foreach ($userPosts as $u): ?>
            <a href="details.php?id=<?php echo $u['id']; ?>"><img src="images/<?php echo $u['url_cropped'] ?>"></a>
        <?php endforeach; ?>
    </div>


    <!-- <a href="editProfile.php" class="btnProfile"> Edit Profile</a>
    <a href="editPassword.php" class="btnProfile"> Edit Password</a>
    <a href="logout.php" class="btnProfile">Logout</a> -->
</div>
<script src="js/navigation.js"></script>
<script src="js/profileMenu.js"></script>

</body>
</html>
