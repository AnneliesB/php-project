<?php
require_once("bootstrap/bootstrap.php");

if(isset($_SESSION["email"])) {
    $uid = User::getUserId();
    try {
        $id = $_GET['id'];
        $post = Post::getPostById($id);
        if(!$post){
            header("Location: /");
        }
    } catch (\PDOException $e) {
        // Log to error file
    }
}else {
    // User is not logged in
    header("Location: /login.php");
}

try {
    if(isset($_POST["edit"])){
        $description = htmlspecialchars($_POST["description"]);
        if((!isset($description) || trim($description) === '')){

        }else{
            if(Post::editPost($uid, $id, $description)){
                header("Location: /details.php?id=$id");
            }
        }
    }
    if(isset($_POST["delete"])){
        if(Post::deletePost($id, $uid)){
        header("Location: /");
        }
    }
} catch (\PDOException $e){
    // Log to error file
    die();
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/normalize.css">
    <title>IMSTAGRAM - edit post</title>
</head>
<body>
<?php include_once("nav.incl.php"); ?>
<form action="" method="POST" enctype="multipart/form-data" class="editProfile">
    <h2 class="profileHeading">Edit post</h2>
    <?php if (isset($error)): ?>
        <div class="formError">
            <p>
                <?php echo $error ?>
            </p>
        </div>
    <?php endif; ?>

        <div class="formField">
            <label for="description">Edit description:</label>
            <textarea rows="10" cols="30" id="description" name="description"
                      class="textarea"><?php echo $post['description']; ?></textarea>
        </div>

        



        <input type="submit" value="Update post" name="edit" class="btn btnPrimary">
    <input type="submit" value="remove post" name="delete" class="btn btnPrimary">



</form>
</body>
</html>