<?php 
    require_once("bootstrap/bootstrap.php");
    // GET id of post
    $id = $_GET['id'];

    //var_dump($id);

    // Connection
    $conn = Db::getConnection();

    // GET description and picture
    $statement = $conn->prepare("select * from photo where id = :id");
    $statement->bindParam(":id", $id);
    $statement->execute();
    $post = $statement->fetch(PDO::FETCH_ASSOC);

    // GET comments
    $commentStatement = $conn->prepare("select comment.*, user.username from comment inner join user on comment.user_id = user.id where post_id = :postId");
    $commentStatement->bindParam(":postId", $id);
    $commentStatement->execute();
    $comments = $commentStatement->fetchAll();

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <title>IMDSTAGRAM - detail page</title>
</head>
<body class="index">

    <header>

    </header>

    <main>
        <!-- echo picture -->
        <img src="images/<?php echo $post['url']; ?>" alt="Post picture">

        <!-- echo description -->
        <p><?php echo $post['description']; ?></p>

        <?php if( !empty($comments) ){ ?>        
            <!-- echo comments -->
            <?php foreach($comments as $comment): ?>
                <div class="comments">
                    <!-- echo timestamp, username and comment -->
                    <p> <?php echo $comment['date'] . " " . $comment['username'] . " " . $comment['comment'] ?> </p>
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

    </main>

    <footer>
    
    </footer>
    
</body>
</html>