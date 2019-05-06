<?php
require_once("bootstrap/bootstrap.php");

//Check if user session is active (Is user logged in?)
if (isset($_SESSION['email'])) {
    //User is logged in, no redirect needed!
} else {
    //User is not logged in, redirect to login.php!
    header("location: login.php");
}

// Get username from username paramater in URL
$username = $_GET['username'];

//get user_id of the loggedin user
$user_id = User::getUserId();

//retrieve all information for this user from the DB via User class
$profile = Follow::getUserProfile($username);

//check if following this user
$follows = Follow::isFollowing($user_id, $profile['id']);

//Get all posts for this user profile
$userPosts = User::getUserPosts($profile['id']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/normalize.css">
    <title><?php echo $username; ?></title>
</head>
<body>
<?php include_once("nav.incl.php"); ?>

<div class="profile">
    <h2><?php echo $profile['username']; ?></h2>

    <?php if ($profile['image'] != "filler.png"): ?>
        <img src="images/profilePictures/<?php echo $profile['id'] . $profile['image']; ?>" alt="Profile Picture"
             class="profilePicture">
    <?php else: ?>
        <img src="images/profilePictures/filler.png" alt="Profile Picture" class="profilePicture">
    <?php endif ?>

    <div class="profileContainer lastItem">
        <p class="profileLabel">Description</p>
        <p><?php echo $profile['description']; ?></p>
    </div>

    <?php if (Follow::NotTryingToFollowMyself($user_id, $profile['id'])): ?>
        <a href="#" id="followBtn" class="btnProfile" data-id="<?php echo $profile['id'] ?>"><?php echo $follows ?></a>
    <?php endif; ?>

    <div class="userPosts">
        <?php foreach ($userPosts as $u): ?>
            <a href="details.php?id=<?php echo $u['id']; ?>"><img src="images/<?php echo $u['url_cropped'] ?>"></a>
        <?php endforeach; ?>
    </div>


</div>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="js/follow.js"></script>
<script src="js/navigation.js"></script>
</body>
</html>