
<?php
require_once("bootstrap/bootstrap.php");

//Check if user session is active (Is user logged in?)
if (isset($_SESSION['email'])) {
    //User is logged in, no redirect needed!
} else {
    //User is not logged in, redirect to login.php!
    header("location: login.php");
}

// GET id of post
$id = $_GET['id'];
//var_dump($id);
// Connection
$conn = Db::getConnection();
$userId = User::getUserId();
// GET current post
$post = Image::getPostById($id);
//get the username of the user that has posted this image
$username = Image::getPostUsername($id);
$comments = Image::getCommentsByPostId($post["id"]);
// GET comments

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/cssgram.css">
    <title>IMDSTAGRAM - detail page</title>
</head>
<body class="details">
<?php include_once("nav.incl.php"); ?>

<header>

</header>


    <main>
        <div class="postContainer">
        

<main class="feed">
    <div class="postContainer">
      <!-- echo edit button -->
        <?php
        if($userId === $post["user_id"]){
            echo "<a href=\"editPost.php?id=$id\" class=\"btnEdit\" >edit post</a>";
        }
        ?>

        <!-- echo picture -->

        <div class="postTopBar">
            <div class="topBar--flex topBar--username">
                <a href="userProfile.php?username=<?php echo htmlspecialchars($username); ?>">
                    <div class="postUsername"><?php echo htmlspecialchars($username); ?></div>
                </a>
                <p class="timeAgo"><?php echo Image::timeAgo($post['time']); ?></p>
            </div>

            <div class="topBar--flex topBar--report">
                <?php if (User::userHasReported($post['id'], $userId) == true): ?>
                    <a href="#" data-id="<?php echo $post['id'] ?>" class="inappropriate inappropriatedLink">
                        <img src="images/report.svg" alt="grey button" class="inappropriateIcon">
                    </a>

                <?php else: ?>
                    <a href="#" data-id="<?php echo $post['id'] ?>" class="inappropriate">
                        <img src="images/report.svg" alt="red button" class="inappropriateIcon">
                    </a>
                <?php endif ?>
            </div>

        </div>

        <p class="postLocation"><?php echo htmlspecialchars($post['city']); ?></p>

        <!-- echo picture -->
        <div class="detailsFilter">
            <div class="<?php echo htmlspecialchars($post['filter']); ?>">
                <img src="images/<?php echo htmlspecialchars($post['url']); ?>" alt="Post picture">
            </div>
        </div>


        <!-- echo description -->
        <p><?php echo htmlspecialchars($post['description']); ?></p>
        <div class="postStats">
            <div>
                <?php if (Like::userHasLiked($post['id'], $uid) == true) : ?>
                    <a href="#" data-id="<?php echo $post['id'] ?>" class="like"><img
                                class="icon postLikeIcon"
                                src="images/liked.svg"
                                alt="like icon"></a>
                <?php else: ?>
                    <a href="#" data-id="<?php echo $post['id'] ?>" class="like"><img
                                class="icon postLikeIcon"
                                src="images/like.svg"
                                alt="like icon"></a>
                <?php endif ?>

                <p class="postLikes"><?php echo Like::getLikeAmount($post['id']); ?></p>
            </div>
            <div class="colorBlock">
                <a href="index.php?color=<?php echo $post['color1']; ?>"
                   style="background-color:<?php echo "#" . $post['color1'] ?>;" class="colorBtn">
                    <p><?php echo $post['color1'] ?></p></a>
                <a href="index.php?color=<?php echo $post['color2']; ?>"
                   style="background-color:<?php echo "#" . $post['color2'] ?>;" class="colorBtn">
                    <p><?php echo $post['color2'] ?></p></a>
                <a href="index.php?color=<?php echo $post['color3']; ?>"
                   style="background-color:<?php echo "#" . $post['color3'] ?>;" class="colorBtn">
                    <p><?php echo $post['color3'] ?></p></a>
                <a href="index.php?color=<?php echo $post['color4']; ?>"
                   style="background-color:<?php echo "#" . $post['color4'] ?>;" class="colorBtn">
                    <p><?php echo $post['color4'] ?></p></a>
            </div>
            <div>
                <p class="postComments">0<?php //echo number of comments ?></p>
                <img class="icon postCommentIcon" src="images/comment.svg" alt="comments icon">
            </div>
        </div>
<div class="commentContainer">
<div id="commentContainer">
        <?php if( !empty($comments) ){ ?>        
            <!-- echo comments -->
            <?php foreach($comments as $comment): ?>
                <div class="comments">
                    <!-- echo timestamp, username and comment -->
                    <p> <?php echo htmlspecialchars($comment['username']) . ": " . htmlspecialchars($comment['comment']) ?> </p>
                </div>
            <?php endforeach;?>
            
        
        <?php } //Closing if
            else{ //No comments ?>
                <p class="postContainer">Be the first to comment!</p>
        <?php } //Closing else ?>
        </div>        
        <div id="<?php echo $id; ?>">
            <input class="commentInput" type="text" name="comment" placeholder="comment...">
            <!--
                Change to button and don't post the form
                onClick="() => sendpostmethod(<\?php echo $id; ?>)"
            -->
          <!--  <input class="commentBtn" type="submit" value="Post">  -->
          <button class="commentBtn" type="button" data-id="<?php echo $id; ?>">send</button>
      </div>
        </div>

</main>

<footer>


    <footer>
    
    </footer>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="js/saveLikes.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="js/post.js"></script>
    <script>
    changePosts(<?php echo($id); ?>);
    </script>
</body>
</html>