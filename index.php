<?php
require_once("bootstrap/bootstrap.php");
//Check if user session is active (Is user logged in?)
if (isset($_SESSION['email'])) {
    //User is logged in, no redirect needed!
} else {
    //User is not logged in, redirect to login.php!
    header("location: login.php");
}
//Open connection
$conn = Db::getConnection();
//Get ID of logged in user so we can later fetch the posts of users he follows.
$userId = User::getUserId();
//Check if Search is used
if (!empty($_GET['query'])) {
    $query = $_GET['query'];
    $results = Image::searchPosts($query);
} 

else if (!empty($_GET['color'])) {
    $color = $_GET['color'];
    $results = Image::showImagesWithTheSameColor($color);
}

else if(!empty($_GET['tag'])) {
    $tag = $_GET['tag'];
    $results = Image::getPostsByTag($tag);
    $follows = Follow::isFollowingHashTag($userId, $tag);

}

else {
    // No Search
    // Show 20 posts of friends on startpage
    // Get hashtags that a user follows
    $hashtags = User::getFollowinghashtags($userId);
    // Get posts from DB and put them in $results
    $results = Image::getAllPosts($userId, $hashtags);
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/normalize.css">
    <title>Feed</title>
</head>
<body class="index">
<?php include_once("nav.incl.php"); ?>

<header>
    <form action="" method="GET">
        <div class="formField">
            <input type="text" id="query" name="query">
            <input type="submit" name="submit" value="Search">
        </div>
    </form>

</header>

<div class="feed">
    <?php
    //Check if no post results (no friends or posts of friends found)
    if (!empty($results)) { 
        //Posts of friends found, display them with a loop

        // If you search hashtags
        if (isset($_GET['tag'])): ?>
            <a id="followHashtagBtn" data-tag="<?php echo '#' . $_GET['tag'] ?>" href=""><?php echo $follows . ' #' . $_GET['tag'] ?></a>
        <!-- Close if -->
        <?php endif;
        

        foreach ($results as $result): ?>

        <div class="postContainer">

            <div class="postTopBar">

                <a href="userProfile.php?username=<?php echo htmlspecialchars($result['username']); ?>"><div class="postUsername"><?php echo htmlspecialchars($result['username']); ?></div></a>
                
                <p><?php echo Image::timeAgo($result['time']); ?></p>
                
                <img class="icon postOptions" src="images/menu.svg" alt="options icon">

                

                <?php if(User::userHasReported($result['id'], $userId) == true): ?>
                    <a href="#" data-id="<?php echo $result['id'] ?>" class="inappropriate inappropriatedLink">Inappropiate</a>

                <?php else: ?>
                    <a href="#" data-id="<?php echo $result['id'] ?>" class="inappropriate">Inappropiate</a>
                <?php endif ?>



            </div>


             <a href="details.php?id=<?php echo $result['id']; ?>"><img class="postImg"src="images/<?php echo $result['url_cropped'] ?>"> </a>

                <p class="postDescription"><?php echo preg_replace( '/\#([A-Za-z0-9]*)/is', ' <a href="index.php?tag=$1">#$1</a> ', htmlspecialchars($result['description']));?></p>                

                <div class="postStats">
                    <div>
                        <?php if (Like::userHasLiked($result['id'], $userId) == true) : ?>
                            <a href="#" data-id="<?php echo $result['id'] ?>" class="like"><img
                                        class="icon postLikeIcon"
                                        src="images/liked.svg"
                                        alt="like icon"></a>
                        <?php else: ?>
                            <a href="#" data-id="<?php echo $result['id'] ?>" class="like"><img
                                        class="icon postLikeIcon"
                                        src="images/like.svg"
                                        alt="like icon"></a>
                        <?php endif ?>

                        <p class="postLikes"><?php echo Like::getLikeAmount($result['id']); ?></p>
                    </div>
                    <div class="colorBlock">
                        <a href="index.php?color=<?php echo $result['color1']; ?>"
                           style="background-color:<?php echo "#" . $result['color1'] ?>;" class="colorBtn">
                            <p><?php echo $result['color1'] ?></p></a>
                        <a href="index.php?color=<?php echo $result['color2']; ?>"
                           style="background-color:<?php echo "#" . $result['color2'] ?>;" class="colorBtn">
                            <p><?php echo $result['color2'] ?></p></a>
                        <a href="index.php?color=<?php echo $result['color3']; ?>"
                           style="background-color:<?php echo "#" . $result['color3'] ?>;" class="colorBtn">
                            <p><?php echo $result['color3'] ?></p></a>
                        <a href="index.php?color=<?php echo $result['color4']; ?>"
                           style="background-color:<?php echo "#" . $result['color4'] ?>;" class="colorBtn">
                            <p><?php echo $result['color4'] ?></p></a>
                    </div>
                    <div>
                        <p class="postComments">0<?php //echo number of comments ?></p>
                        <img class="icon postCommentIcon" src="images/comment.svg" alt="comments icon">
                    </div>
                </div>


                <form action="" method="POST">
                    <input class="commentInput" type="text" name="comment" placeholder="comment...">
                    <input class="commentBtn" type="submit" value="Post" data-id="<?php echo $result['id'] ?>">
                </form>



            </div>


        <?php endforeach; ?>

        <a>
            <div class="loadMoreBtn grow">Load More</div>
        </a>

    <?php } //Closing if
    else { //No posts of friends found, show empty state message
        ?>

        <p class="postContainer">Try following friends and other users to see what they have been up to!</p>

    <?php } //Closing else ?>
</div>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="js/saveLikes.js"></script>
    <script src="js/loadMore.js"></script>
    <script src="js/inappropriate.js"></script>
    <script src="js/followHashtag.js"></script>

</body>
</html>