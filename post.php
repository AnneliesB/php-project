<?php 
    require_once("bootstrap/bootstrap.php");

    //Check if user session is active (Is user logged in?)
    if( isset($_SESSION['email']) ){
        //User is logged in, no redirect needed!
    }else{
        //User is not logged in, redirect to login.php!
        header("location: login.php");
    }

    if(!empty($_POST)){  
        // UPLOAD image
        if(isset($_POST['upload'])) {
            // GET image name / filename / description
            $image = Image::getPostId() . $_FILES['image']['name'];
            $imageSaveName = $_FILES['image']['tmp_name'];

            $croppedImage = Image::getPostId() . "cropped-" .$_FILES['image']['name'];
            $description = $_POST['description'];  

            Image::saveImageToDb($image, $croppedImage, $description);          
            Image::saveImage($imageSaveName, $target);
            Image::saveCroppedImage($image);

        }
    }    

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>IMSTAGRAM - add post</title>
</head>

<body>
    <form action="" method="POST" enctype="multipart/form-data">
        <?php if (isset($error)): ?>
            <div class="formError">
                <p>
                    <?php echo $error ?>
                </p>
            </div>
        <?php endif; ?>

        <h2 formTitle>Add post</h2>

        <div class="flexbox">
            <div class="formField">
                <label for="image">Picture</label>
                <input type="file" id="image" name="image">
            </div>
            <div class="formField">
                <label for="description">Description</label>
                
                <textarea id="description" name="description" rows="10" > </textarea>
            </div>
        </div>

        <div class="formField">
            <input type="submit" value="Post" name="upload" class="btn btnPrimary">
        </div>
    </form>
</body>

</html>