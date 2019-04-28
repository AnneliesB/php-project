<?php 
    require_once("bootstrap/bootstrap.php");

    //Check if user session is active (Is user logged in?)
    if( isset($_SESSION['email']) ){
        //User is logged in, no redirect needed!
    }else{
        //User is not logged in, redirect to login.php!
    header("location: login.php");
    }

    //Open connection
    $conn = Db::getConnection();

    //Get ID of logged in user so we can later fetch the posts of users he follows.
    $userId = User::getUserId();

    //Check if Search is used
    if(!empty($_GET['query'])){ 
        $query = $_GET['query'];
        $statement = $conn->prepare("select photo.*, user.username from photo INNER JOIN user ON photo.user_id = user.id where photo.description like '%". $query ."%'"); 
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
    if( !empty($results) ){

        //Posts of friends found, display them with a loop
        foreach($results as $result): ?>

        <!-- If inappropriate = 3, hide post -->        
        <?php if(Image::postHas3Reports($result['id']) == true): ?>
        <div class="postContainer disabled">
        <?php else: ?>
        <div class="postContainer">
        <?php endif ?>


            <div class="postTopBar">
                <div class="postUsername"><?php echo $result['username'] ?></div>
                <img class="icon postOptions" src="images/menu.svg" alt="options icon">

                <?php if(User::userHasReported($result['id'], $userId) == true): ?>
                    <a href="#" data-id="<?php echo $result['id'] ?>" class="inappropriate inappropriatedLink">Inappropiate</a>
                
                <?php else: ?>
                    <a href="#" data-id="<?php echo $result['id'] ?>" class="inappropriate">Inappropiate</a>
                <?php endif ?>



            </div>

            <a href="details.php?id=<?php echo $result['id']; ?>"><img class="postImg" src="images/<?php echo $result['url_cropped'] ?>"> </a>
            <p class="postDescription"><?php echo $result['description'] ?></p>

            <div class="postStats">
                <div>
                    <a href=""><img class="icon postLikeIcon" src="images/like.svg" alt="like icon"></a>
                    <p class="postLikes">0<?php //echo number of likes ?></p>
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

    else{ //No posts of friends found, show empty state message ?>

        <p class="postContainer">Try following friends and other users to see what they have been up to!</p>
    
    <?php } //Closing else ?>
</div>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="js/loadMore.js"></script>
    <script src="js/inappropiate.js"></script>
</body>
</html>