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
if (!empty($_GET['query']) || (isset($_GET["category"]) && !empty($_GET["category"]))) {
    $query = "%" . $_GET['query'] . "%";
    $sql = "select photo.*, user.username from photo INNER JOIN user ON photo.user_id = user.id where photo.description like ? and photo.inappropriate = 0";
    if(isset($_GET["category"]) && !empty($_GET["category"])){
        $sql .= " AND category_id = " . $_GET["category"];
    }
    $sql .= " order by id desc LIMIT 2";
    $statement = $conn->prepare($sql);
    $statement->bindParam("1", $query);
    $statement->execute();
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
} else if (!empty($_GET['color'])) {
    $color = $_GET['color'];
    $results = Image::showImagesWithTheSameColor($color);
} else {
    //No Search
    //Show 20 posts of friends on startpage
    //Get posts from DB and put them in $results
    $statement = $conn->prepare("select photo.*, user.username, photo.id from photo INNER JOIN user ON photo.user_id = user.id where user_id IN ( select following_id from followers where user_id = :user_id ) and photo.inappropriate = 0 order by id desc limit 2");
    $statement->bindParam(":user_id", $userId);
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
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/cssgram.css">
    <title>Feed</title>
</head>
<body class="index">
<?php include_once("nav.incl.php"); ?>

<header>
    <form action="" method="GET">
        <div class="searchBar" id="search">
            <input type="text" id="query" name="query">
            <select name="category" id="category">
                <option value="">None</option>
                <option value="1">Lineart</option>
                <option value="2">Emblems</option>
                <option value="3">Logotypes</option>
                <option value="4">Monogram Logo's</option>
                <option value="5">Brand Marks</option>
                <option value="6">Abstract Logo Marks</option>
                <option value="7">Mascots</option>
                <option value="8">Combination marks</option>
            </select>
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


                    <a href="userProfile.php?username=<?php echo htmlspecialchars($result['username']); ?>">
                        <div class="postUsername"><?php echo htmlspecialchars($result['username']); ?></div>
                    </a>

                    <p><?php echo Image::timeAgo($result['time']); ?></p>

                    <img class="icon postOptions" src="images/menu.svg" alt="options icon">


                    <?php if (User::userHasReported($result['id'], $userId) == true): ?>
                        <a href="#" data-id="<?php echo $result['id'] ?>" class="inappropriate inappropriatedLink">Inappropiate</a>

                    <?php else: ?>
                        <a href="#" data-id="<?php echo $result['id'] ?>" class="inappropriate">Inappropiate</a>
                    <?php endif ?>


                </div>


                    <a href="details.php?id=<?php echo $result['id']; ?>">
                        <div class="indexFilter">
                            <div class="<?php echo $result['filter']; ?>">
                                <img class="postImg" src="images/<?php echo $result['url_cropped'] ?>">
                            </div>
                        </div>
                    </a>



                <p class="postDescription"><?php echo htmlspecialchars($result['description']) ?></p>

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

        <div class="loadMoreContainer">
            <a>
                <div class="loadMoreBtn grow">Load More</div>
            </a>
        </div>

    <?php } //Closing if
    else { //No posts of friends found, show empty state message
        ?>

        <p class="postContainer">Try following friends and other users to see what they have been up to!</p>

    <?php } //Closing else ?>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="js/saveLikes.js"></script>
<script src="js/loadMore.js"></script>
<script src="js/inappropriate.js"></script>
<script src="js/navigation.js"></script>

</body>
</html>