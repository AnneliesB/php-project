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
$user_id = User::getUserId();

//Check if Search is used
if (!empty($_POST['query'])) {
    $query = $_POST['query'];
    $statement = $conn->prepare("select description, url from photo where description like '%" . $query . "%'");
    $statement->execute();
} else {
    //No Search
    //Show 20 posts of friends on startpage

    //Check on how many posts should be loaded
    if (!isset($_POST['loadMore'])) {
        //initial load, show initial number of posts
        $posts = 2;
    } else {
        //if page reloaded by loadmore button, update current value from btn
        $posts = $_POST['loadMore'];
    }
    if (!empty($_POST['loadMore'])) {
        //add extra posts to show
        $posts += 2;
    }

    //Get posts from DB and put them in $results
    $statement = $conn->prepare("select photo.*, user.username, photo.id from photo INNER JOIN user ON photo.user_id = user.id where user_id IN ( select following_id from followers where user_id = :user_id ) order by id desc limit $posts");
    $statement->bindParam(":user_id", $user_id);
    $statement->execute();
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
}



    //Open connection
    $conn = Db::getConnection();

    //Get ID of logged in user so we can later fetch the posts of users he follows.
    $user_id = User::getUserId();

    //Check if Search is used
    if(!empty($_GET['query'])){ 
        $query = $_GET['query'];
        $statement = $conn->prepare("select photo.*, user.username from photo INNER JOIN user ON photo.user_id = user.id where photo.description like '%". $query ."%' order by id desc LIMIT 2"); 
        $statement->execute();   
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);    
    }

    else {
        //No Search
        //Show 20 posts of friends on startpage
        
        //Get posts from DB and put them in $results
        $statement = $conn->prepare("select photo.*, user.username from photo INNER JOIN user ON photo.user_id = user.id where user_id IN ( select following_id from followers where user_id = :user_id ) order by id desc limit 2");
        $statement->bindParam(":user_id", $user_id);
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    }




?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Feed</title>
</head>
<body class="index">

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
        foreach ($results as $result): ?>

            <div class="postContainer">

            <div class="postTopBar">
                <div class="postUsername"><?php echo $result['username'] ?></div>
                <img class="icon postOptions" src="images/menu.svg" alt="options icon">
            </div>


            <a href="details.php?id=<?php echo $result['id']; ?>"><img class="postImg" src="images/<?php echo $result['url_cropped'] ?>"> </a>

            <p class="postDescription"><?php echo $result['description'] ?></p>

            <div class="postStats">
            <div>
            <?php if (Like::userHasLiked($result['id'], $user_id) == true) : ?>
                <a href="#" data-id="<?php echo $result['id'] ?>" class="like"><img class="icon postLikeIcon"
                                                                                    src="images/liked.svg"
                                                                                    alt="like icon"></a>
            <?php else: ?>
                    <a href="#" data-id="<?php echo $result['id'] ?>" class="like"><img class="icon postLikeIcon"
                                                                                        src="images/like.svg"
                                                                                        alt="like icon"></a>
                <?php endif ?>

                <p class="postLikes"><?php echo Like::getLikeAmount($result['id']); ?></p>
                </div>
                <div>
                    <p class="postComments">0<?php //echo number of comments ?></p>
                    <img class="icon postCommentIcon" src="images/comment.svg" alt="comments icon">
                </div>
                </div>


                <form>
                    <input class="commentInput" type="text" name="comment" placeholder="comment...">
                    <input class="commentBtn" type="submit" value="Post">
                </form>


                </div>

      

        <?php endforeach; ?>

        <a><div class="loadMoreBtn grow">Load More</div></a>
        
    <?php } //Closing if


    else { //No posts of friends found, show empty state message
        ?>

        <p class="postContainer">Try following friends and other users to see what they have been up to!</p>

    <?php } //Closing else ?>
</div>

<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="js/saveLikes.js"></script>
    <script src="js/loadMore.js"></script>
</body>
</html>