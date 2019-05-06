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
            $city = $_POST['city'];
            
            if (Image::checkExtention($image)) {
                // If extention is png or jpeg
                Image::saveImageToDb($image, $croppedImage, $description, $city);
                Image::saveImage($image, $imageSaveName);
                Image::saveCroppedImage($image);
                Image::saveMainColors($image);
                header("location: index.php");
            } 
            else {
                // Else error message
                $error = "You can only upload png or jpg.";
            }
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
    <link rel="stylesheet" href="css/normalize.css">
    <title>IMSTAGRAM - add post</title>
</head>

<body class="post">
<?php include_once("nav.incl.php"); ?>
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

            <!-- hidden fields to store lat, lng and city on upload-->
            <div class="hidden">
                <label for="city">City</label>
                <input type="text" name="city" id="city">
            </div>
            <div class="hidden">
                <label for="lat">City</label>
                <input type="text" name="lat" id="lat">
            </div>
            <div class="hidden">
                <label for="lng">City</label>
                <input type="text" name="lng" id="lng">
            </div>
        </div>

        <div class="formField">
            <input type="submit" value="Post" name="upload" class="btn btnPrimary">
        </div>
    </form>
    <script src="js/postLocation.js"></script>
</body>

</html>