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
    $user_id = User::getUserId();

    //Check if Search is used
    if(!empty($_POST)){ 
        $query = $_POST['query'];
        $statement = $conn->prepare("select description, url from photo where description like '%". $query ."%'"); 
        $statement->execute();       
    }

    else {
        //No Search
        //Show 20 posts of friends on startpage
        $statement = $conn->prepare("select photo.*, user.username from photo INNER JOIN user ON photo.user_id = user.id where user_id IN ( select following_id from followers where user_id = :user_id ) order by id desc limit 20");
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

<div class="feed">
<?php 
    //Check if no post results (no friends or posts of friends found)
    if( !empty($results) ){

        //Posts of friends found, display them with a loop
        foreach($results as $result): ?>

        <div class="postContainer">

            <div class="postTopBar">
                <div class="postUsername"><?php echo $result['username'] ?></div>
                <img class="icon postOptions" src="images/menu.svg" alt="options icon">
            </div>

            <img class="postImg" src="images/<?php echo $result['url_cropped'] ?>"> 
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
    
</body>
</html>