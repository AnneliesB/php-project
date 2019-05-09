<?php 
    require_once("bootstrap/bootstrap.php");
    
    // GET id of post
    $uid = User::getUserId();
    $id = $_GET['id'];
    $post = Image::getPostById($id);
    $comments = Image::getCommentsByPostId($post["id"]);
    //var_dump($id);
    // Connection
    $conn = Db::getConnection();
    $userId = User::getUserId();
    // GET description and picture
    /*$statement = $conn->prepare("select * from photo where id = :id");
    $statement->bindParam(":id", $id);
    $statement->execute();
    $post = $statement->fetch(PDO::FETCH_ASSOC);*/
    //key value user id vergelijken met session ID
    
    // GET comments
    /*$commentStatement = $conn->prepare("select comment.*, user.username from comment inner join user on comment.user_id = user.id where post_id = :postId AND enable=0");
    $commentStatement->bindParam(":postId", $id);
    $commentStatement->execute();
    $comments = $commentStatement->fetchAll();*/
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/normalize.css">
    <title>IMDSTAGRAM - detail page</title>
</head>
<body class="index">
<?php include_once("nav.incl.php"); ?>

    <header>

    </header>

    <main>
        <div class="postContainer">
        <!-- echo edit button -->
        <?php
        if($uid === $post["user_id"]){
            echo "<a href=\"editPost.php?id=$id\" class=\"btnEdit\" >edit post</a>";
        }
        ?>
        <!-- echo picture -->
        <img src="images/<?php echo $post['url']; ?>" alt="Post picture">

        <!-- echo description -->
        <p><?php echo htmlspecialchars($post['description']); ?></p>
        <div class="postStats">
            <div>
                <?php if (Like::userHasLiked($post['id'], $userId) == true) : ?>
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
                <a href="index.php?color=<?php echo ltrim($post['color1'], '#'); ?>"
                   style="background-color:<?php echo $post['color1'] ?>;" class="colorBtn">
                    <p><?php echo $post['color1'] ?></p></a>
                <a href="index.php?color=<?php echo ltrim($post['color2'], '#'); ?>"
                   style="background-color:<?php echo $post['color2'] ?>;" class="colorBtn">
                    <p><?php echo $post['color2'] ?></p></a>
                <a href="index.php?color=<?php echo ltrim($post['color3'], '#'); ?>"
                   style="background-color:<?php echo $post['color3'] ?>;" class="colorBtn">
                    <p><?php echo $post['color3'] ?></p></a>
                <a href="index.php?color=<?php echo ltrim($post['color4'], '#'); ?>"
                   style="background-color:<?php echo $post['color4'] ?>;" class="colorBtn">
                    <p><?php echo $post['color4'] ?></p></a>
            </div>
            <div>
                <p class="postComments">0<?php //echo number of comments ?></p>
                <img class="icon postCommentIcon" src="images/comment.svg" alt="comments icon">
            </div>
        </div>
<div class="commentContainer">
        <?php if( !empty($comments) ){ ?>        
            <!-- echo comments -->
            <?php foreach($comments as $comment): ?>
                <div class="comments">
                    <!-- echo timestamp, username and comment -->
                    <p> <?php echo htmlspecialchars($comment['date']) . " " . htmlspecialchars($comment['username']) . " " . htmlspecialchars($comment['comment']) ?> </p>
                </div>
            <?php endforeach;?>
            
        
        <?php } //Closing if
            else{ //No comments ?>
                <p class="postContainer">Be the first to comment!</p>
        <?php } //Closing else ?>

        <form>
            <input class="commentInput" type="text" name="comment" placeholder="comment...">
            <input class="commentBtn" type="submit" value="Post">
        </form>
        </div>

    </main>

    <footer>
    
    </footer>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="js/saveLikes.js"></script>
</body>
</html>