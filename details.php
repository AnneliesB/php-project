<?php 
    require_once("bootstrap/bootstrap.php");
    // GET id of post
    $id = $_GET['id'];

    // Connection
    $conn = Db::getConnection();

    // GET description and picture
    $statement = $conn->prepare("select * from photo where id = :id");
    $statement->bindParam(":id", $id);
    $statement->execute();
    $post = $statement->fetch(PDO::FETCH_ASSOC);

    // GET comments
    $commentStatement = $conn->prepare("select * from comment where post_id = :postId");
    $commentStatement->bindParam(":postId", $id);
    $commentStatement->execute();
    $comments = $commentStatement->fetchAll();

    // GET user of comment
    $userStatement = $conn->prepare("select * from user where user_id = :userId");
    $commentStatement->bindParam(":postId", $id);
    $commentStatement->execute();




?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <title>IMDSTAGRAM - detail page</title>
</head>
<body>

    <header>
    
    </header>

    <main>
        <!-- echo picture -->
        <img src="images/<?php echo $post['url']; ?>" alt="Post picture">

        <!-- echo description -->
        <p><?php echo $post['description']; ?></p>

        <!-- echo comments -->
        <?php foreach($comments as $comment): ?>
            <!-- echo username -->
            <p><strong>  </strong></p>

            <!-- echo comment -->
            <p> </p>
        <?php endforeach;?>

    </main>

    <footer>
    
    </footer>
    
</body>
</html>