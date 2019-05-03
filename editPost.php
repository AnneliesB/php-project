<?php

require_once("bootstrap/bootstrap.php");

//Check if user session is active (Is user logged in?)
if (isset($_SESSION['id'])) {
    //User is logged in, no redirect needed!
    try{

        //try catch moet korter!
        $conn = Db::getConnection();
        $id = $_GET['id'];
        $statement = $conn->prepare("select * from photo where id = :id AND user_id = :uid");
        $statement->bindParam(":id", $id);
        $statement->bindParam(":uid", $_SESSION["id"]);
        $statement->execute();
        $post = $statement->fetch(PDO::FETCH_ASSOC);
        if(!$post){
            header("Location: /");
        }
    }catch(\PDOException $e){
        // Log error to file
    }
} else {
    //User is not logged in, redirect to login.php
    header("location: /login.php");
}

if(isset($_POST["edit"])){
    var_dump($_POST);
    //AANPASSEN
    $updateStatement = $conn->prepare("UPDATE photo SET description=:description WHERE id=:id AND user_id=:uid";)
    $updateStatement->bindParam(":newDescription", $description);
    $updateStatement->execute();
    // Redirect to the details of the post
    // Location: /details.php?id=$id
    header("location: /details.php?id=$id");
}
if(isset($_POST["delete"])){
    $updateStatement = $conn->prepare("UPDATE photo SET enable=1 WHERE id=:id AND user_id=:uid";)
    $updateStatement->bindParam(":enable", $enable);
    $updateStatement->bindParam(":sessionEmail", $sessionEmail);
    $updateStatement->execute();
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
    <input type="submit" value="remove post" name="delete" class="btn btnDanger">



</form>
</body>
</html>